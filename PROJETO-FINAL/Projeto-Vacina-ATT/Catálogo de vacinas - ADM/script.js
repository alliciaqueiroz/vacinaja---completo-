function displayImage(input, boxId) {
    console.log('Imagem carregada no box', boxId); // Verificação
    const image = document.getElementById('vaccineImage' + boxId);
    const topSpace = document.getElementById('topSpace' + boxId);
    const reader = new FileReader();

    reader.onload = function(e) {
        image.src = e.target.result;
        image.style.display = "block";
        topSpace.classList.add('has-image');
    }

    if (input.files && input.files[0]) {
        reader.readAsDataURL(input.files[0]);
    }
}
