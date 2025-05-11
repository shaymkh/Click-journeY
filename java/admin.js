// 1. Injection du CSS
const style = document.createElement('style');
style.textContent = `
  .admin-container {
    max-width: 800px;
    margin: 40px auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }
  .admin-container h1 {
    text-align: center;
    margin-bottom: 20px;
  }
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: left;
  }
  .switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
  }
  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }
  .slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
  }
  .slider:before {
    position: absolute;
    content: "";
    height: 18px; width: 18px;
    left: 3px; bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
  }
  input:checked + .slider {
    background-color: #5d74b8;
  }
  input:checked + .slider:before {
    transform: translateX(26px);
  }
  input[disabled] + .slider {
    background-color: #eee;
    cursor: not-allowed;
  }
  .loading {
    margin-left: 8px;
    font-weight: bold;
  }
`;
document.head.appendChild(style);

// 2. Simulation d’attente sur les switches
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.role-switch').forEach(switchEl => {
    switchEl.addEventListener('change', () => {
      // Désactive le switch et ajoute un loader
      switchEl.disabled = true;
      const cell = switchEl.closest('td');
      const loader = document.createElement('span');
      loader.className = 'loading';
      loader.textContent = '…';
      cell.appendChild(loader);

      // Simulation d'un appel serveur de 2 secondes
      setTimeout(() => {
        // Retire le loader
        loader.remove();
        // Réactive le switch
        switchEl.disabled = false;

        //  phase 4
      }, 2000);
    });
  });
});

