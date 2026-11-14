/**
 * Designer Mode: Multi-Channel Tabs & High-Res PNG / ZIP Export Module
 */
import { showToast } from '../common/toast.js';

export function initChannelExport() {
    // 1. Channel Tabs Logic (Instagram, WhatsApp, LinkedIn)
    const tabBtns = {
        ig: document.getElementById('tab-btn-ig'),
        wa: document.getElementById('tab-btn-wa'),
        li: document.getElementById('tab-btn-li')
    };

    const tabContents = {
        ig: document.getElementById('channel-content-ig'),
        wa: document.getElementById('channel-content-wa'),
        li: document.getElementById('channel-content-li')
    };

    function switchChannel(channel) {
        Object.keys(tabBtns).forEach(k => {
            if (!tabBtns[k] || !tabContents[k]) return;
            if (k === channel) {
                tabBtns[k].classList.add('active', 'bg-indigo-600', 'text-white');
                tabBtns[k].classList.remove('text-slate-400');
                tabContents[k].classList.remove('hidden');
            } else {
                tabBtns[k].classList.remove('active', 'bg-indigo-600', 'text-white');
                tabBtns[k].classList.add('text-slate-400');
                tabContents[k].classList.add('hidden');
            }
        });
    }

    if (tabBtns.ig) tabBtns.ig.addEventListener('click', () => switchChannel('ig'));
    if (tabBtns.wa) tabBtns.wa.addEventListener('click', () => switchChannel('wa'));
    if (tabBtns.li) tabBtns.li.addEventListener('click', () => switchChannel('li'));

    // 2. Copy WhatsApp Broadcast Text
    const copyWaBtn = document.getElementById('copy-wa-btn');
    if (copyWaBtn) {
        copyWaBtn.addEventListener('click', () => {
            const waText = document.getElementById('wa-broadcast-text')?.innerText || '';
            navigator.clipboard.writeText(waText).then(() => {
                const orig = copyWaBtn.innerHTML;
                copyWaBtn.innerHTML = '✓ Tersalin!';
                showToast('Format pesan WhatsApp berhasil disalin!', 'success');
                setTimeout(() => copyWaBtn.innerHTML = orig, 2000);
            });
        });
    }

    // 3. Copy LinkedIn Post Text
    const copyLiBtn = document.getElementById('copy-li-btn');
    if (copyLiBtn) {
        copyLiBtn.addEventListener('click', () => {
            const liText = document.getElementById('li-post-text')?.innerText || '';
            navigator.clipboard.writeText(liText).then(() => {
                const orig = copyLiBtn.innerHTML;
                copyLiBtn.innerHTML = '✓ Tersalin!';
                showToast('Postingan LinkedIn berhasil disalin!', 'success');
                setTimeout(() => copyLiBtn.innerHTML = orig, 2000);
            });
        });
    }

    // 4. Export Dropdown Menu
    const exportDropdownContainer = document.getElementById('export-dropdown-container');
    const exportDropdownBtn = document.getElementById('export-dropdown-btn');
    const exportDropdownMenu = document.getElementById('export-dropdown-menu');

    if (exportDropdownBtn && exportDropdownMenu) {
        exportDropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            exportDropdownMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!exportDropdownContainer?.contains(e.target)) {
                exportDropdownMenu.classList.add('hidden');
            }
        });
    }

    // 5. Download Helper
    function triggerBlobDownload(blob, filename) {
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    // 6. Slide to PNG renderer
    async function renderSlideToPng(slideElement, width, height) {
        if (typeof window.htmlToImage === 'undefined') {
            throw new Error('Library html-to-image belum termuat. Pastikan browser terkoneksi ke internet.');
        }

        const clone = slideElement.cloneNode(true);
        clone.classList.remove('hidden');
        clone.classList.add('flex');
        clone.style.width = width + 'px';
        clone.style.height = height + 'px';
        clone.style.margin = '0';
        clone.style.boxSizing = 'border-box';

        const wrapper = document.createElement('div');
        wrapper.style.position = 'fixed';
        wrapper.style.left = '-9999px';
        wrapper.style.top = '0';
        wrapper.style.width = width + 'px';
        wrapper.style.height = height + 'px';
        wrapper.style.overflow = 'hidden';
        wrapper.style.zIndex = '-9999';
        wrapper.appendChild(clone);
        document.body.appendChild(wrapper);

        try {
            let dataUrl;
            try {
                dataUrl = await window.htmlToImage.toPng(clone, {
                    pixelRatio: 2,
                    cacheBust: true,
                    backgroundColor: '#070B14'
                });
            } catch (fontErr) {
                console.warn('Font embed warning, retrying without embedded font styles:', fontErr);
                dataUrl = await window.htmlToImage.toPng(clone, {
                    pixelRatio: 2,
                    fontEmbedCSS: '',
                    backgroundColor: '#070B14'
                });
            }
            return dataUrl;
        } finally {
            document.body.removeChild(wrapper);
        }
    }

    // Get current Job ID
    function getJobId() {
        const jobIdEl = document.querySelector('[data-job-id]');
        return jobIdEl?.getAttribute('data-job-id') || 'BRIEFLY-MEDIA';
    }

    // 7. Single PNG Download Action
    async function executeDownloadSinglePng(buttonEl) {
        const activeSlide = document.querySelector('#slide-carousel .slide-item:not(.hidden)');
        if (!activeSlide) return;

        const slideNum = document.getElementById('current-slide-num')?.textContent || '1';
        const origHtml = buttonEl.innerHTML;
        buttonEl.innerHTML = '<span>Mengonversi...</span>';
        buttonEl.disabled = true;

        try {
            const width = activeSlide.offsetWidth || 448;
            const height = activeSlide.offsetHeight || width || 448;
            const dataUrl = await renderSlideToPng(activeSlide, width, height);

            const a = document.createElement('a');
            a.href = dataUrl;
            a.download = `${getJobId()}-slide-${slideNum}.png`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

            showToast(`Slide ${slideNum} berhasil diunduh (PNG)!`, 'success');
        } catch (err) {
            console.error('Download PNG failed:', err);
            showToast('Gagal mengunduh slide: ' + (err.message || 'Render error'), 'error');
        } finally {
            buttonEl.innerHTML = origHtml;
            buttonEl.disabled = false;
            exportDropdownMenu?.classList.add('hidden');
        }
    }

    // 8. All Slides ZIP Download Action
    async function executeDownloadAllZip(buttonEl) {
        if (typeof window.JSZip === 'undefined') {
            showToast('Library JSZip belum termuat.', 'error');
            return;
        }

        const slides = document.querySelectorAll('#slide-carousel .slide-item');
        if (!slides.length) return;

        const origHtml = buttonEl.innerHTML;
        buttonEl.disabled = true;

        const activeSlide = document.querySelector('#slide-carousel .slide-item:not(.hidden)');
        const width = activeSlide?.offsetWidth || 448;
        const height = activeSlide?.offsetHeight || width || 448;

        const zip = new window.JSZip();
        const jobId = getJobId();
        const folder = zip.folder(jobId);

        try {
            for (let i = 0; i < slides.length; i++) {
                buttonEl.innerHTML = `<span>Proses Slide ${i + 1}/${slides.length}...</span>`;
                const dataUrl = await renderSlideToPng(slides[i], width, height);
                const base64Data = dataUrl.replace(/^data:image\/png;base64,/, '');
                folder.file(`slide-${i + 1}.png`, base64Data, { base64: true });
            }

            buttonEl.innerHTML = '<span>Mengompresi ZIP...</span>';
            const zipBlob = await zip.generateAsync({ type: 'blob' });
            triggerBlobDownload(zipBlob, `${jobId}-all-slides.zip`);

            showToast(`Paket ${slides.length} slide berhasil diunduh dalam format .ZIP!`, 'success');
        } catch (err) {
            console.error('Download ZIP failed:', err);
            showToast('Gagal mengekspor slide ke ZIP: ' + (err.message || 'Render error'), 'error');
        } finally {
            buttonEl.innerHTML = origHtml;
            buttonEl.disabled = false;
            exportDropdownMenu?.classList.add('hidden');
        }
    }

    // Bind triggers
    document.getElementById('download-current-slide-btn')?.addEventListener('click', function() {
        executeDownloadSinglePng(this);
    });
    document.getElementById('canvas-download-png-btn')?.addEventListener('click', function() {
        executeDownloadSinglePng(this);
    });

    document.getElementById('download-all-zip-btn')?.addEventListener('click', function() {
        executeDownloadAllZip(this);
    });
    document.getElementById('canvas-download-zip-btn')?.addEventListener('click', function() {
        executeDownloadAllZip(this);
    });
}
