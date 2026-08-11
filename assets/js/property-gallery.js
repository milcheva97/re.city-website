const galleryDialog = document.querySelector('.gallery-dialog');
const galleryImages = galleryDialog ? [...galleryDialog.querySelectorAll('.gallery-dialog-grid img')] : [];

const openGallery = (index = 0) => {
  if (!galleryDialog) return;
  galleryDialog.showModal();
  document.body.style.overflow = 'hidden';
  requestAnimationFrame(() => galleryImages[index]?.scrollIntoView({ block: 'start' }));
};

document.querySelector('[data-gallery-open]')?.addEventListener('click', () => openGallery());
document.querySelectorAll('[data-gallery-index]').forEach((tile) => {
  tile.addEventListener('click', () => openGallery(Number(tile.dataset.galleryIndex)));
});
document.querySelector('[data-gallery-close]')?.addEventListener('click', () => galleryDialog?.close());
galleryDialog?.addEventListener('click', (event) => {
  if (event.target === galleryDialog) galleryDialog.close();
});
galleryDialog?.addEventListener('close', () => { document.body.style.overflow = ''; });
