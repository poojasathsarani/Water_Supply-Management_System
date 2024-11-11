function triggerFileUpload() {
    document.getElementById('profilePicUpload').click();
}

function loadFile(event) {
    const output = document.getElementById('profilePic');
    const icon = document.getElementById('profileIcon');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.style.display = 'block';
    icon.style.display = 'none';
    output.onload = function() {
        URL.revokeObjectURL(output.src);
    }
}

document.getElementById('profileForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);
    fetch('/upload', {
        method: 'POST',
        body: formData
    }).then(response => response.json())
      .then(data => console.log(data))
      .catch(error => console.error(error));
});
