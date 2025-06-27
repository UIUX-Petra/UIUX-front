<div id="announcement-container" class="announcement-wrapper my-4" style="display: none;">
    <div class="swiper announcement-swiper">
        <div class="swiper-wrapper">

        </div>
    </div>

    <div class="swiper-button-prev announcement-nav-btn" style="display: none;"></div>
    <div class="swiper-button-next announcement-nav-btn" style="display: none;"></div>

    <button id="announcement-close-btn" class="announcement-close-btn bg-gradient-to-r from-[#38A3A5] to-[#80ED99]" title="Tutup">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>

<style>
    .announcement-wrapper {
        position: relative;
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 0.75rem;
        padding: 0.5rem 6rem 0.5rem 3.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        min-height: 54px;
        display: flex;
        align-items: center;
    }

    .announcement-wrapper.is-loading::before {
        content: 'Memuat pengumuman...';
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    .light-mode .announcement-wrapper {
        box-shadow: 0 4px 20px var(--bg-shadow);
    }

    .announcement-swiper {
        flex-grow: 1;
        overflow: hidden;
        margin: 0;
        padding: 0;
    }

    .swiper-slide,
    .announcement-content {
        display: flex;
        align-items: center;
    }

    .announcement-content {
        color: var(--text-secondary);
        font-size: 0.875rem;
        width: 100%;
    }

    .announcement-icon {
        color: var(--accent-primary);
        font-size: 1.25rem;
        margin-right: 1rem;
        animation: pulse-icon 2.5s infinite ease-in-out;
    }

    @keyframes pulse-icon {
        0%,
        100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
    }

    .announcement-text {
        line-height: 1.4;
    }

    .announcement-text strong {
        color: var(--text-primary);
        margin-right: 0.5rem;
    }

    .announcement-nav-btn {
        color: var(--text-muted);
        width: 32px;
        height: 32px;
        top: 70%;
        transform: translateY(-50%);
        transition: color 0.2s ease, background-color 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 5;
    }

    .announcement-nav-btn:hover {
        color: var(--text-primary);
        background-color: var(--bg-card-hover);
        border-radius: 50%;
    }

    .announcement-nav-btn::after {
        font-size: 0.8rem;
        font-weight: bold;
    }

    .swiper-button-prev {
        left: 0.75rem;
    }

    .swiper-button-next {
        right: 3rem;
    }

    .announcement-close-btn {
        position: absolute;
        top: 40%;
        transform: translateY(-50%);
        right: 0.75rem;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background-color: var(--bg-tertiary);
        border: none;
        color: var(--text-primary);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        z-index: 10;
    }

    .announcement-close-btn:hover {
        opacity: 0.8;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .swiper-button-disabled {
        display: none;
    }

    .see-more-btn {
        background: none;
        border: 1px solid transparent;
        color: var(--accent-primary);
        font-weight: 500;
        cursor: pointer;
        margin-left: 0.5rem;
        padding: 4px 8px;
        border-radius: 6px;
        transition: all 0.2s ease;
        display: inline-block;
    }

    .see-more-btn:hover {
        background-color: var(--bg-tertiary);
        color: var(--text-primary);
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .swal2-popup.announcement-modal {
        background-color: var(--bg-secondary) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 1rem !important;
    }

    .swal2-title.announcement-modal-title {
        color: var(--text-primary) !important;
    }

    .swal2-html-container.announcement-modal-content {
        color: var(--text-secondary) !important;
        text-align: left !important;
        line-height: 1.6;
    }

    .swal2-styled.swal2-confirm.swal2-confirm-button-gradient {
        background: linear-gradient(to right, #38A3A5, #80ED99) !important;
        color: var(--button-text, #000000) !important;
        font-weight: 600 !important;
        border: 0 !important;
        border-radius: 0.5rem !important;
        padding: 0.65rem 1.5rem !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    }

    .swal2-styled.swal2-confirm.swal2-confirm-button-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15) !important;
        filter: brightness(1.1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('announcement-container');
        const closeBtn = document.getElementById('announcement-close-btn');

        const apiBaseUrl = '{{ env('API_BASE_URL', 'http://127.0.0.1:8001') }}/api';
        const apiUrl = `${apiBaseUrl}/announcements/active`;
        const apiToken = "{{ session('token') }}";

        if (!apiToken) {
            console.log("Tidak ada token otentikasi untuk mengambil pengumuman.");
            return;
        }

        if (sessionStorage.getItem('announcementClosed') === 'true') {
            container.style.display = 'none';
            return;
        }

        const showAnnouncementDetail = (title, detail) => {
            Swal.fire({
                title: title,
                html: `<div style="max-height: 40vh; overflow-y: auto; padding-right: 15px;">${detail.replace(/\n/g, '<br>')}</div>`,
                showCloseButton: false,
                confirmButtonText: 'Tutup',
                customClass: {
                    popup: 'announcement-modal',
                    title: 'announcement-modal-title',
                    htmlContainer: 'announcement-modal-content',
                    confirmButton: 'swal2-confirm-button-gradient'
                }
            });
        };

        const fetchAnnouncements = async () => {
            container.style.display = 'flex';
            container.classList.add('is-loading');

            try {
                const response = await fetch(apiUrl, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${apiToken}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `HTTP error! Status: ${response.status}`);
                }

                const result = await response.json();
                if (result.success && result.data.length > 0) {
                    container.classList.remove('is-loading');
                    populateAnnouncements(result.data);
                } else {
                    container.style.display = 'none';
                }
            } catch (error) {
                console.error("Gagal mengambil pengumuman:", error);
                container.style.display = 'none';
            }
        };

        const populateAnnouncements = (announcements) => {
            const swiperWrapper = container.querySelector('.swiper-wrapper');
            swiperWrapper.innerHTML = '';

            announcements.forEach(ann => {
                const detailText = ann.detail;
                const isLongText = detailText.length > 120;
                const truncatedText = isLongText ? detailText.substring(0, 120) + '...' :
                detailText;

                const slide = `
                <div class="swiper-slide">
                    <div class="announcement-content">
                        <i class="fa-solid fa-bullhorn announcement-icon"></i>
                        <div class="announcement-text">
                            <strong>${ann.title}:</strong>
                            <span class="detail-text">${truncatedText}</span>
                            ${isLongText ? `<button class="see-more-btn" data-full-title="${ann.title}" data-full-detail="${escape(detailText)}">Lihat Selengkapnya</button>` : ''}
                        </div>
                    </div>
                </div>
                `;
                swiperWrapper.insertAdjacentHTML('beforeend', slide);
            });

            if (announcements.length > 1) {
                container.querySelector('.swiper-button-prev').style.display = 'flex';
                container.querySelector('.swiper-button-next').style.display = 'flex';
            }

            new Swiper('.announcement-swiper', {
                loop: announcements.length > 1,
                autoplay: {
                    delay: 8000,
                    disableOnInteraction: false
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                },
            });

            container.querySelectorAll('.see-more-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const fullTitle = this.dataset.fullTitle;
                    const fullDetail = unescape(this.dataset.fullDetail);
                    showAnnouncementDetail(fullTitle, fullDetail);
                });
            });
        };

        closeBtn.addEventListener('click', function() {
            container.style.transition =
                'opacity 0.3s ease, transform 0.3s ease, margin-bottom 0.3s ease, padding 0.3s ease, min-height 0.3s ease';
            container.style.opacity = '0';
            container.style.transform = 'translateY(-20px)';
            container.style.marginBottom = '-1rem';
            container.style.paddingTop = '0';
            container.style.paddingBottom = '0';
            container.style.minHeight = '0';

            setTimeout(() => container.style.display = 'none', 300);
            sessionStorage.setItem('announcementClosed', 'true');
        });

        fetchAnnouncements();
    });
</script>
