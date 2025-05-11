// Injection du CSS
const style = document.createElement('style');
style.textContent = `
  body { font-family: Arial; padding: 20px; background: #f5f5f5; }
  .navbar ul { list-style:none; display:flex; gap:10px; padding:0; }
  .navbar a { text-decoration:none; color:#333; }
  .admin-container {
    max-width: 800px; margin: 20px auto; background: #fff;
    padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }
  table { width:100%; border-collapse: collapse; margin-bottom:20px; }
  th, td { padding:10px; border:1px solid #ddd; text-align:center; }
  .switch { position:relative; display:inline-block; width:50px; height:24px; }
  .switch input { opacity:0; width:0; height:0; }
  .slider {
    position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0;
    background-color:#ccc; transition:.4s; border-radius:24px;
  }
  .slider:before {
    position:absolute; content:""; height:18px; width:18px;
    left:3px; bottom:3px; background:white; transition:.4s; border-radius:50%;
  }
  input:checked + .slider { background-color:#5d74b8; }
  input:checked + .slider:before { transform: translateX(26px); }
  input[disabled] + .slider { background:#eee; cursor:not-allowed; }
  .loading { margin-left:8px; font-weight:bold; }
  .pagination a { margin:0 5px; text-decoration:none; color:#333; }
  .pagination a.current { font-weight:bold; }
`;
document.head.appendChild(style);

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('change', () => {
      btn.disabled = true;
      const cell = btn.closest('td');
      const loader = document.createElement('span');
      loader.className = 'loading';
      loader.textContent = '…';
      cell.appendChild(loader);

      setTimeout(() => {
        loader.remove();
        btn.disabled = false;
        // Visual toggle only:
        // en phase 4, appelez ici votre API avec fetch()
      }, 2000);
    });
  });
});


