async function loadCompany() {
    try {
        const res = await fetch("../backend/actions/company/get_company.php");
        const [c] = await res.json(); // lấy phần tử đầu tiên
        if(c) {
            const logo = document.getElementById("company-logo");
            const name = document.getElementById("company-name");
            const email = document.getElementById("company-email");
            const sdt = document.getElementById("company-sdt");
            
            if(logo) logo.src = c.logo;
            if(name) name.textContent = c.name;
            if(email) email.textContent = c.email_support;
            if(sdt) sdt.textContent = c.website;
        }
    } catch(err) {
        console.error("Không thể load company info:", err);
    }
}

loadCompany();
