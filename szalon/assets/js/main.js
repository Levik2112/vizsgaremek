console.log("Szalon rendszer betöltve");


function toggleLoginPassword() {
    const input = document.getElementById('login-password');
    input.type = input.type === 'password' ? 'text' : 'password';
}

