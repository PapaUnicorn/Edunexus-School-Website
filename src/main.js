// Import Bootstrap JS
import 'bootstrap';
// Import Stylesheet
import './style.scss';

// State to store current config
let currentConfig = null;

// Convert upload paths if they refer to the old data folder
function getAssetUrl(url) {
  if (!url) return '';
  if (url.startsWith('/data/uploads/')) {
    return 'assets/images/' + url.replace('/data/uploads/', '');
  }
  if (url.startsWith('data/uploads/')) {
    return 'assets/images/' + url.replace('data/uploads/', '');
  }
  return url;
}

// Inject Brand Color CSS
function injectBrandColor(color) {
  if (!color) return;
  
  // Set root CSS variable
  document.documentElement.style.setProperty('--warna-utama', color);
  
  const existingStyle = document.getElementById('brandColorOverride');
  if (existingStyle) existingStyle.remove();
  
  const style = document.createElement('style');
  style.id = 'brandColorOverride';
  style.innerHTML = `
    body {
      --primary-color: ${color} !important;
    }
    .btn-primary, .btn-login {
      background-color: ${color} !important;
      border-color: ${color} !important;
      color: #FFF !important;
    }
    .btn-primary:hover, .btn-login:hover {
      filter: brightness(0.95) !important;
      color: #FFF !important;
    }
    .text-primary, .navbar .nav-link.active, .navbar .nav-link:hover, .navbar-brand {
      color: ${color} !important;
    }
    .bg-gradient-card {
      background: linear-gradient(135deg, ${color} 0%, #0d131f 100%) !important;
    }
    .contact-icon {
      background-color: ${color} !important;
    }
    .theme-playful .btn-primary {
      box-shadow: 0 6px 0 rgba(0,0,0,0.2) !important;
    }
  `;
  document.head.appendChild(style);
}

// Function to render preview content dynamically based on configuration updates from the dashboard
function renderConfig(config) {
  currentConfig = config;

  // 1. Theme mapping:
  const themeMap = {
    'tema-nova': 'classic',
    'tema-siber': 'modern',
    'tema-lestari': 'eco',
    'tema-minimalis': 'minimalist',
    'tema-ceria': 'playful'
  };
  const activeThemeClass = themeMap[config.sistem?.tema_aktif] || themeMap[config.tema_aktif] || 'classic';

  // Set theme class on body
  document.body.className = '';
  document.body.classList.add(`theme-${activeThemeClass}`);
  
  // Apply brand color override
  const warna = config.sekolah?.warna_brand || config.warna_brand;
  injectBrandColor(warna);
  
  // Map specific DOM nodes
  const metaTitle = document.getElementById('meta-title');
  if (metaTitle) metaTitle.innerText = config.sekolah?.nama || config.sekolah_nama || '';
  
  const txtNamaSekolah = document.getElementById('txt-nama-sekolah');
  if (txtNamaSekolah) txtNamaSekolah.innerText = config.sekolah?.nama || config.sekolah_nama || '';
  
  const imgLogo = document.getElementById('img-logo');
  if (imgLogo) {
    const logoUrl = config.sekolah?.logo || config.logo_url;
    imgLogo.src = logoUrl ? getAssetUrl(logoUrl) : '';
    imgLogo.style.display = logoUrl ? 'block' : 'none';
  }

  const txtHeroTitle = document.getElementById('txt-hero-title');
  if (txtHeroTitle) txtHeroTitle.innerText = config.beranda?.teks_hero || config.beranda?.hero_banner?.headline || '';

  const txtHeroSub = document.getElementById('txt-hero-sub');
  if (txtHeroSub) txtHeroSub.innerText = config.beranda?.sub_hero || config.beranda?.hero_banner?.sub_hero || '';

  const heroBanner = document.getElementById('hero-banner');
  if (heroBanner) {
    const heroImg = config.beranda?.gambar_hero || config.beranda?.hero_banner?.image_url;
    if (heroImg) {
      heroBanner.style.backgroundImage = `url('${getAssetUrl(heroImg)}')`;
    } else {
      heroBanner.style.backgroundImage = 'none';
    }
  }

  // 4. Principal message
  const principalPhoto = document.getElementById('principalPhoto');
  if (principalPhoto) {
    const pPhoto = config.beranda?.photo || config.beranda?.kepala_sekolah?.foto;
    principalPhoto.src = pPhoto ? getAssetUrl(pPhoto) : 'assets/images/principal_photo.jpg';
  }
  
  const principalMessage = document.getElementById('principalMessage');
  if (principalMessage) principalMessage.textContent = config.beranda?.pesan_kepsek || config.beranda?.kepala_sekolah?.sambutan || '';
  
  const principalName = document.getElementById('principalName');
  if (principalName) principalName.textContent = config.beranda?.nama_kepsek || config.beranda?.kepala_sekolah?.nama || '';

  // 5. About us (Profil)
  const aboutTitle = document.getElementById('aboutTitle');
  if (aboutTitle) aboutTitle.textContent = config.profil?.about_title || '';
  
  const aboutDescription = document.getElementById('aboutDescription');
  if (aboutDescription) aboutDescription.textContent = config.profil?.about_description || '';

  const visionText = document.getElementById('visionText');
  if (visionText) visionText.textContent = config.profil?.vision || '';

  const missionList = document.getElementById('missionList');
  if (missionList) {
    missionList.innerHTML = '';
    const missions = config.profil?.mission || [];
    missions.forEach((m) => {
      const li = document.createElement('li');
      li.className = 'mb-2 d-flex align-items-start';
      li.innerHTML = `<i class="bi bi-check-circle-fill me-2 text-warning mt-1"></i><span>${m}</span>`;
      missionList.appendChild(li);
    });
  }

  // 6. Majors
  const majorsContainer = document.getElementById('majorsContainer');
  if (majorsContainer) {
    majorsContainer.innerHTML = '';
    const majors = config.majors || [];
    majors.forEach((major) => {
      const col = document.createElement('div');
      col.className = 'col-md-4';
      col.innerHTML = `
        <div class="card h-100 p-4 border-0 hover-lift shadow-sm">
          <div class="card-icon mb-3 fs-1 text-primary">
            <i class="bi ${major.icon || 'bi-mortarboard-fill'}"></i>
          </div>
          <h4 class="card-title fw-bold font-heading mb-3">${major.name}</h4>
          <p class="card-text text-secondary font-body">${major.description}</p>
        </div>
      `;
      majorsContainer.appendChild(col);
    });
  }

  // 7. Facilities
  const facilitiesContainer = document.getElementById('facilitiesContainer');
  if (facilitiesContainer) {
    facilitiesContainer.innerHTML = '';
    const facilities = config.facilities || [];
    facilities.forEach((fac, idx) => {
      const icons = ['bi-laptop', 'bi-camera-video', 'bi-book', 'bi-router', 'bi-door-open', 'bi-building'];
      const icon = icons[idx % icons.length];
      
      const col = document.createElement('div');
      col.className = 'col-md-6 col-lg-3';
      col.innerHTML = `
        <div class="card h-100 p-4 border-0 hover-lift shadow-sm text-center">
          <div class="card-icon mb-3 fs-2 text-primary">
            <i class="bi ${icon}"></i>
          </div>
          <h5 class="card-title fw-bold font-heading mb-2">${fac.name}</h5>
          <p class="card-text text-secondary font-body small mb-0">${fac.description}</p>
        </div>
      `;
      facilitiesContainer.appendChild(col);
    });
  }

  // 8. Extracurriculars
  const ekskulContainer = document.getElementById('ekskulContainer');
  if (ekskulContainer) {
    ekskulContainer.innerHTML = '';
    const extracurriculars = config.extracurriculars || [];
    extracurriculars.forEach((ekskul, idx) => {
      const icons = ['bi-people-fill', 'bi-compass-fill', 'bi-code-square', 'bi-controller', 'bi-music-note-beamed', 'bi-palette-fill'];
      const icon = icons[idx % icons.length];
      
      const col = document.createElement('div');
      col.className = 'col-md-4 col-lg-3';
      col.innerHTML = `
        <div class="card h-100 p-4 border-0 hover-lift shadow-sm text-center">
          <div class="card-icon mb-3 fs-3 text-primary">
            <i class="bi ${icon}"></i>
          </div>
          <h6 class="card-title fw-bold font-heading mb-2">${ekskul.name}</h6>
          <p class="card-text text-secondary font-body small mb-0">${ekskul.description}</p>
        </div>
      `;
      ekskulContainer.appendChild(col);
    });
  }

  // 9. Contact Info
  const contactAddress = document.getElementById('contactAddress');
  if (contactAddress) contactAddress.textContent = config.contact?.address || '';
  
  const contactPhone = document.getElementById('contactPhone');
  if (contactPhone) contactPhone.textContent = config.contact?.phone || '';
  
  const contactEmail = document.getElementById('contactEmail');
  if (contactEmail) contactEmail.textContent = config.contact?.email || '';

  // 10. Footer / NPSN
  const footerSchoolName = document.getElementById('footerSchoolName');
  if (footerSchoolName) {
    footerSchoolName.textContent = config.sekolah?.nama || config.sekolah_nama || 'Sekolah';
  }

  const heroAccreditation = document.getElementById('heroAccreditation');
  if (heroAccreditation) {
    heroAccreditation.textContent = `NPSN: ${config.sekolah?.npsn || config.npsn || '12345678'}`;
  }
}

// Extractor Blogger API
function loadBloggerNews(blogId, apiKey) {
  const container = document.getElementById('news-grid-container');
  const newsSourceLabel = document.getElementById('newsSourceLabel');
  if (!container) return;

  if (!blogId || !apiKey || blogId === "MASUKKAN_BLOG_ID_DI_SINI" || apiKey === "MASUKKAN_API_KEY_DI_SINI") {
    return;
  }

  const endpoint = `https://www.googleapis.com/blogger/v3/blogs/${blogId}/posts?key=${apiKey}&maxResults=4&fetchImages=true`;
  
  fetch(endpoint)
    .then(res => {
      if (!res.ok) throw new Error('Blogger API request failed');
      return res.json();
    })
    .then(data => {
      if (newsSourceLabel) {
        newsSourceLabel.innerHTML = '<span class="badge bg-success"><i class="bi bi-google"></i> Live Blogger API</span>';
      }
      container.innerHTML = '';
      
      if (data.items && data.items.length > 0) {
        data.items.forEach(post => {
          let thumbnail = post.images && post.images.length > 0 
              ? post.images[0].url 
              : 'assets/images/placeholder.jpg';

          const cardHTML = `
            <div class="col-md-3 mb-4">
              <div class="card h-100 border-0 shadow-sm overflow-hidden news-card hover-lift">
                <div style="height: 180px; overflow: hidden;">
                  <img src="${thumbnail}" class="w-100 h-100 object-fit-cover" alt="Thumbnail">
                </div>
                <div class="card-body news-content d-flex flex-column p-3">
                  <h5 class="fw-bold font-heading mb-2" style="font-size: 1.05rem;">${post.title}</h5>
                  <p class="text-muted small font-body mb-3">${new Date(post.published).toLocaleDateString('id-ID')}</p>
                  <a href="${post.url}" target="_blank" class="btn btn-outline-primary btn-sm btn-read rounded-pill mt-auto align-self-start font-accent">Baca Selengkapnya</a>
                </div>
              </div>
            </div>
          `;
          container.insertAdjacentHTML('beforeend', cardHTML);
        });
      } else {
        container.innerHTML = `
          <div class="col-12 text-center text-muted py-4">
            <i class="bi bi-newspaper fs-2"></i>
            <p class="mt-2 font-body">Tidak ada artikel di Blogger.</p>
          </div>
        `;
      }
    })
    .catch(err => {
      console.warn("Blogger API failed:", err);
    });
}

// Initial triggers on DOM content ready
document.addEventListener("DOMContentLoaded", () => {
  if (window.bloggerConfig) {
    loadBloggerNews(window.bloggerConfig.blogId, window.bloggerConfig.apiKey);
  }
});

// Handle iframe live updates
window.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'PREVIEW_UPDATE') {
    renderConfig(event.data.config);
  }
});
