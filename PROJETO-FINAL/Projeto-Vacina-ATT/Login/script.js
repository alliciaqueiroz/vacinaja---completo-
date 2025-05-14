
  document.querySelector(".eye-svg").addEventListener("click", function() {
    const senhaInput = document.getElementById("senha");
    const type = senhaInput.type === "password" ? "text" : "password";
    senhaInput.type = type;
});



