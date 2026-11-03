<x-layouts.app title="Konfigurasi Agen AI — Briefly Admin">
    <div class="space-y-6">
        <!-- Admin Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2 border-b border-white/10">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                    <span>Konfigurasi Agen AI & Prompt Templates</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-300 border border-amber-500/20 font-semibold">Administrator</span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Atur batasan pemanggilan model LLM, prompt sistem, mekanisme schema guardrail, dan batasan eksekusi tool.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left 6 cols: Parameter Configuration -->
            <div class="lg:col-span-6 space-y-5">
                <div class="glass-panel rounded-2xl p-6 space-y-4">
                    <h2 class="text-xs font-bold text-white uppercase tracking-wider">Parameter Model LLM & Guardrail</h2>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Model LLM Utama</label>
                        <select class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:border-amber-400">
                            <option value="gemini-1.5-flash" selected>Google Gemini 1.5 Flash (Cepat & Efisien)</option>
                            <option value="gemini-1.5-pro">Google Gemini 1.5 Pro (Reasoning Mendalam)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Temperature</label>
                            <input type="text" value="0.4" readonly class="w-full px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-slate-300 text-xs font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Maks. Retry Guardrail</label>
                            <input type="text" value="3x Retries" readonly class="w-full px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-slate-300 text-xs font-mono">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Top-K Similarity RAG</label>
                            <input type="text" value="K = 4 Chunks" readonly class="w-full px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-slate-300 text-xs font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Status Gemini API</label>
                            <div class="px-4 py-2 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs font-semibold flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                <span>Engine Ready</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-panel rounded-2xl p-6 space-y-3">
                    <h2 class="text-xs font-bold text-white uppercase tracking-wider">Aturan Fallback Otomatis</h2>
                    <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 text-xs text-slate-300 space-y-2">
                        <div class="font-semibold text-amber-300">Rule Fallback: INCOMPLETE_DATA</div>
                        <p class="leading-relaxed">Jika informasi esensial (nama acara atau tanggal) tidak ditemukan pada brief maupun RAG, sistem secara transaksional menandai status <strong>INCOMPLETE_DATA</strong> dan membatalkan penyusunan slide.</p>
                    </div>
                </div>
            </div>

            <!-- Right 6 cols: System Prompt Template Inspector -->
            <div class="lg:col-span-6 space-y-5">
                <div class="glass-panel rounded-2xl p-6 space-y-3">
                    <h2 class="text-xs font-bold text-white uppercase tracking-wider flex items-center justify-between">
                        <span>System Prompt Template</span>
                        <span class="text-[10px] text-indigo-400 font-mono">Structured Output</span>
                    </h2>
                    <p class="text-xs text-slate-400">Prompt sistem yang disuntikkan ke agen untuk memastikan konsistensi luaran format JSON:</p>
                    <pre class="p-4 rounded-xl bg-slate-950 border border-slate-800 text-[11px] font-mono text-indigo-200 leading-relaxed overflow-x-auto max-h-96">You are an expert Social Media & Graphic Content Production AI Agent.
Output ONLY strict JSON conforming to this schema without markdown fences:
{
  "job_id": string,
  "event_metadata": {
    "event_name": string,
    "event_date": string,
    "contact_person": string
  },
  "branding_rules_applied": {
    "primary_color_hex": string (hex pattern ^#([A-Fa-f0-9]{6})$),
    "secondary_color_hex": string (hex pattern ^#([A-Fa-f0-9]{6})$),
    "font_family_recommendation": string
  },
  "visual_slides_layout": [
    {
      "slide_number": integer,
      "layout_template": "cover" | "content_list" | "quote" | "cta",
      "headline": string (MAX 50 CHARS),
      "body_text": [string]
    }
  ],
  "social_media_caption": {
    "hook_sentence": string,
    "body_paragraph": string,
    "call_to_action": string,
    "hashtags": [string]
  },
  "workflow_status": "PENDING_REVIEW"
}</pre>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
