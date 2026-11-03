<?php

namespace App\Http\Controllers;

use App\Models\BrandGuideline;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display Brand Guidelines and RAG Vector Knowledge management.
     */
    public function guidelines(): View
    {
        $guideline = BrandGuideline::latest()->first() ?? new BrandGuideline([
            'organization_name' => 'Himpunan Mahasiswa Informatika',
            'primary_color_hex' => '#003366',
            'secondary_color_hex' => '#FFCC00',
            'document_path' => 'rag_documents/default_brand_guide.pdf',
            'vector_metadata' => [
                'brand_voice' => 'Informatif, energik, profesional, dan ramah mahasiswa.',
                'font_recommendation' => 'Instrument Sans, Inter, sans-serif',
                'hashtag_rules' => ['#HMIFKampus', '#InformatikaJaya', '#TechEvent2026'],
                'rag_chunks' => [],
            ],
        ]);

        return view('admin.guidelines', compact('guideline'));
    }

    /**
     * Update Brand Guidelines & Vector RAG metadata.
     */
    public function updateGuidelines(Request $request): RedirectResponse
    {
        $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'primary_color_hex' => ['required', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'secondary_color_hex' => ['required', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'brand_voice' => ['required', 'string'],
            'font_recommendation' => ['required', 'string'],
        ]);

        $guideline = BrandGuideline::latest()->first();

        $meta = $guideline?->vector_metadata ?? [];
        $meta['brand_voice'] = $request->input('brand_voice');
        $meta['font_recommendation'] = $request->input('font_recommendation');

        // Handle document upload if provided
        $docPath = $guideline?->document_path ?? 'rag_documents/brand_guideline.pdf';
        if ($request->hasFile('document_file')) {
            $path = $request->file('document_file')->store('rag_documents', 'public');
            $docPath = $path;
        }

        // Add new RAG Chunk if submitted
        if ($request->filled('new_chunk_topic') && $request->filled('new_chunk_content')) {
            $chunks = $meta['rag_chunks'] ?? [];
            $keywords = array_values(array_filter(array_map('trim', explode(',', $request->input('new_chunk_keywords', '')))));
            $chunks[] = [
                'chunk_id' => 'chunk_'.uniqid(),
                'topic' => $request->input('new_chunk_topic'),
                'content' => $request->input('new_chunk_content'),
                'embedding_keywords' => ! empty($keywords) ? $keywords : ['aturan', 'sop', 'konten'],
            ];
            $meta['rag_chunks'] = $chunks;
        }

        if ($guideline) {
            $guideline->update([
                'organization_name' => $request->input('organization_name'),
                'primary_color_hex' => $request->input('primary_color_hex'),
                'secondary_color_hex' => $request->input('secondary_color_hex'),
                'document_path' => $docPath,
                'vector_metadata' => $meta,
            ]);
        } else {
            BrandGuideline::create([
                'organization_name' => $request->input('organization_name'),
                'primary_color_hex' => $request->input('primary_color_hex'),
                'secondary_color_hex' => $request->input('secondary_color_hex'),
                'document_path' => $docPath,
                'vector_metadata' => $meta,
            ]);
        }

        return redirect()->route('admin.guidelines')->with('success', 'Brand Guideline dan Basis Pengetahuan RAG berhasil diperbarui!');
    }

    /**
     * Display AI Configuration and Prompt Settings.
     */
    public function settings(): View
    {
        return view('admin.settings');
    }

    /**
     * Display User Management.
     */
    public function users(): View
    {
        $users = User::latest()->get();

        return view('admin.users', compact('users'));
    }
}
