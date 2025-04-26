document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("loginForm");

    loginForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        try {
            const response = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password }),
            });

            if (response.ok) {
                const data = await response.json();
                alert("Login successful!");
                window.location.href = "/dashboard";
            } else {
                const error = await response.json();
                alert(`Login failed: ${error.message}`);
            }
        } catch (err) {
            console.error("Error during login:", err);
            alert("An error occurred. Please try again.");
        }
    });
});
