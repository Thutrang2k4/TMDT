document.addEventListener("DOMContentLoaded", () => {
  const load = (id, path) => {
    const el = document.getElementById(id);
    if (!el) return;
    fetch(path).then(r=>{ if(!r.ok) throw Error('not found'); return r.text() })
      .then(t=>el.innerHTML = t).catch(()=>{});
  };
  load('header','partials/header.php');
  load('footer','partials/footer.php');
});