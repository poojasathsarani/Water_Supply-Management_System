document.getElementById('status').addEventListener('change', function() {
  const status = this.value;

  document.getElementById('consumer-fields').style.display = 'none';
  document.getElementById('technician-fields').style.display = 'none';
  document.getElementById('serviceprovider-fields').style.display = 'none';

  if (status === 'consumer') {
    document.getElementById('consumer-fields').style.display = 'block';
  } else if (status === 'meterreader') {
    // No additional fields for meter reader
  } else if (status === 'technician') {
    document.getElementById('technician-fields').style.display = 'block';
  } else if (status === 'serviceprovider') {
    document.getElementById('serviceprovider-fields').style.display = 'block';
  }
});

document.getElementById('registerForm').addEventListener('submit', function(e) {
  const password = document.getElementById('password').value;
  const confirmPassword = document.getElementById('confirm-password').value;

  if (password !== confirmPassword) {
    alert("Passwords do not match.");
    e.preventDefault();
    return;
  }

  const requiredFields = document.querySelectorAll('#registerForm input[required], #registerForm select[required]');
  for (const field of requiredFields) {
    if (!field.value) {
      alert("Please fill all the required fields.");
      e.preventDefault();
      return;
    }
  }
});
