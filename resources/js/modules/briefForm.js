/**
 * Requester Brief Form Interactive Module
 */
import { showToast } from './toast.js';

export function initBriefForm() {
    const briefForm = document.getElementById('brief-form');
    const briefTextarea = document.getElementById('raw_brief_text');
    const titleInput = document.getElementById('event_title');
    const charCountEl = document.getElementById('char-count');
    const templateButtons = document.querySelectorAll('.template-btn');
    const submitBtn = document.getElementById('submit-brief-btn');
    const loadingOverlay = document.getElementById('ai-loading-overlay');
    const loadingStatusText = document.getElementById('loading-status-text');

    if (!briefForm || !briefTextarea) return;

    // Live character counter
    function updateCharCount() {
        const len = briefTextarea.value.length;
        if (charCountEl) {
            charCountEl.textContent = `${len} karakter`;
            if (len >= 10) {
                charCountEl.className = 'text-xs text-emerald-400 font-medium';
            } else {
                charCountEl.className = 'text-xs text-slate-400';
            }
        }
    }

    briefTextarea.addEventListener('input', updateCharCount);
    updateCharCount();

    // Sample brief templates for quick testing
    const templates = {
        seminar: {
            title: "National Tech Seminar: Future of Agentic AI 2026",
            text: `Acara: National Tech Seminar 2026
Tanggal: 28 November 2026
Waktu: 09.00 - 15.00 WIB
Lokasi: Auditorium Utama Kampus & Zoom Webinar
Target Peserta: Mahasiswa Teknik Informatika, Sistem Informasi, dan Penggiat Teknologi

Poin Penting:
- Eksplorasi perkembangan Autonomous Agentic AI & Model Reasoning terkini
- Keynote speaker dari Lead AI Engineer unicorn nasional & Dosen Peneliti
- Live hands-on demo pembuatan sistem RAG terintegrasi
- Free E-Certificate (SKP) dan Goodie Bag eksklusif untuk 100 pendaftar pertama

Pendaftaran: Gratis (Kuota Terbatas)
Narahubung: WhatsApp 0812-9876-5432 (Kak Sarah)`
        },
        lomba: {
            title: "Campus Hackathon 2026: Code for Impact",
            text: `Event: Campus Hackathon & Competition 2026
Tanggal: 15-16 Desember 2026
Tempat: Lab Riset Multimedia & Coworking Space Kampus
Peserta: Tim mahasiswa aktif (1 tim 3 orang) se-Indonesia

Sorotan Lomba:
- Total hadiah tunai Rp 25.000.000 + Trophy Rektor
- Kategori solusi: Smart Campus, Sustainability Green Tech, & EduTech
- Mentoring 1-on-1 dari praktisi industri tech ternama
- Kesempatan pitching langsung di depan Angel Investor

Registrasi dibuka hingga 5 Desember 2026.
Contact Person: 0857-1122-3344 (Budi Satria) / IG @hmif_official`
        },
        oprec: {
            title: "Open Recruitment Panitia Media & Kreatif Dies Natalis",
            text: `Kegiatan: Open Recruitment Volunteer Media & Kreatif Dies Natalis ke-40
Waktu Pendaftaran: 1 - 10 November 2026
Sasaran: Seluruh mahasiswa angkatan 2024 - 2026 yang memiliki antusiasme di bidang konten & desain

Divisi yang Dibutuhkan:
- Graphic Designer (Feed Carousel, Story, Poster)
- Copywriter & Social Media Strategist
- Video Editor & Motion Graphic Specialist
- Documentation & Photography Team

Benefit Bergabung:
- Sertifikat Kepanitiaan Resmi Rektorat
- Portofolio nyata produksi media berskala universitas
- Akses ke workshop internal design & media workflow

Link pendaftaran ada di bio profil. Narahubung: Kak Dika (WA: 0877-5544-3322)`
        }
    };

    templateButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const templateKey = e.currentTarget.dataset.template;
            if (templates[templateKey]) {
                titleInput.value = templates[templateKey].title;
                briefTextarea.value = templates[templateKey].text;
                updateCharCount();
                showToast(`Template "${templates[templateKey].title}" berhasil dimuat!`, 'info');
                briefTextarea.focus();
            }
        });
    });

    // Submit handler with animated AI pipeline progress
    briefForm.addEventListener('submit', (e) => {
        if (briefTextarea.value.trim().length < 10) {
            e.preventDefault();
            showToast('Brief minimal harus berisi 10 karakter.', 'warning');
            return;
        }

        if (loadingOverlay) {
            loadingOverlay.classList.remove('hidden');
            loadingOverlay.classList.add('flex');

            const steps = [
                "1/4 Menganalisis entitas & intensi dari brief...",
                "2/4 Menjalankan similarity search pada RAG Brand Guidelines & SOP...",
                "3/4 Membangun slide layout visual dan copywriting Instagram...",
                "4/4 Memvalidasi skema guardrail data formal..."
            ];

            let stepIdx = 0;
            const stepInterval = setInterval(() => {
                stepIdx++;
                if (stepIdx < steps.length && loadingStatusText) {
                    loadingStatusText.textContent = steps[stepIdx];
                } else {
                    clearInterval(stepInterval);
                }
            }, 800);
        }

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Memproses Agen AI...
            `;
        }
    });
}
