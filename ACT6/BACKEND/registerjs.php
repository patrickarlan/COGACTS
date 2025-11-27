<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('form');
  // Remove error state on input for text fields
  const fields = ['reg-username', 'reg-email', 'reg-password', 'reg-confirm-password'];
  fields.forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener('input', function() {
        // Remove error class and tooltip if field is valid
        if (el.classList.contains('error')) {
            if (id === 'reg-email') {
              // Email: must be non-empty, contain '@', a period after '@', and a valid domain ending
              const value = el.value.trim();
              // Simple regex for email validation
              const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
              if (emailPattern.test(value)) {
                el.classList.remove('error');
                const tooltip = el.parentElement.querySelector('.error-tooltip');
                if (tooltip) tooltip.remove();
              }
          } else if (id === 'reg-confirm-password') {
            // Confirm password: must match password
            const passwordEl = document.getElementById('reg-password');
            if (el.value.trim() && passwordEl.value === el.value) {
              el.classList.remove('error');
              const tooltip = el.parentElement.querySelector('.error-tooltip');
              if (tooltip) tooltip.remove();
            }
          } else {
            // Other fields: just non-empty
            if (el.value.trim()) {
              el.classList.remove('error');
              const tooltip = el.parentElement.querySelector('.error-tooltip');
              if (tooltip) tooltip.remove();
            }
          }
        }
      });
    }
  });
  // Remove error state on check for terms checkbox
  const terms = document.getElementById('registerTerms');
  if (terms) {
    terms.addEventListener('change', function() {
      if (terms.checked) {
        terms.classList.remove('error');
        const tooltip = terms.parentElement.querySelector('.error-tooltip');
        if (tooltip) tooltip.remove();
      }
    });
  }

  form.addEventListener('submit', function (e) {
    // Remove previous errors and error classes
    document.querySelectorAll('.error-tooltip').forEach(el => el.remove());
    document.querySelectorAll('.register-control').forEach(el => el.classList.remove('error'));
    document.querySelectorAll('.form-check-input').forEach(el => el.classList.remove('error'));
    let hasError = false;
    const username = document.getElementById('reg-username');
    const email = document.getElementById('reg-email');
    const password = document.getElementById('reg-password');
    const confirmPassword = document.getElementById('reg-confirm-password');
    const terms = document.getElementById('registerTerms');

    // Error checks
    if (!username.value.trim()) {
      showTooltip(username, "Username is required.");
      hasError = true;
    }
    if (!email.value.trim()) {
      showTooltip(email, "Email is required.");
      hasError = true;
    } else if (!email.value.includes('@')) {
      showTooltip(email, "Please include an '@' in the email address. '" + email.value + "' is missing an '@'.");
      hasError = true;
    }
    if (!password.value.trim()) {
      showTooltip(password, "Password is strictly required.");
      hasError = true;
    }
      if (!confirmPassword.value.trim()) {
        showTooltip(confirmPassword, "Please confirm your password.");
        hasError = true;
      } else if (password.value && confirmPassword.value && password.value !== confirmPassword.value) {
        showTooltip(confirmPassword, "Passwords do not match.");
        hasError = true;
      } else {
        // If confirm password is correct, remove error style immediately
        confirmPassword.classList.remove('error');
        const tooltip = confirmPassword.parentElement.querySelector('.error-tooltip');
        if (tooltip) tooltip.remove();
      }
    if (!terms.checked) {
      terms.classList.add('error');
      showTooltip(terms, "You must agree to the Terms and Privacy Policy.", true);
      hasError = true;
    } else {
      terms.classList.remove('error');
        // Remove tooltip if present
        const tooltip = terms.parentElement.querySelector('.error-tooltip');
        if (tooltip) tooltip.remove();
    }
    if (hasError) {
      // Prevent submission if there are errors
      e.preventDefault();
    }
    // If no error, allow normal form submission to backend
  });

  function showTooltip(input, message, isCheckbox) {
    if (isCheckbox) {
      let tooltip = document.createElement('div');
      tooltip.className = 'error-tooltip';
      tooltip.innerHTML = '<span class="error-icon"><i class="bi bi-exclamation-square-fill"></i></span>' + message;
      input.parentElement.insertBefore(tooltip, input.nextSibling);
      return;
    }
    input.classList.add('error');
    let tooltip = document.createElement('div');
    tooltip.className = 'error-tooltip';
    tooltip.innerHTML = '<span class="error-icon"><i class="bi bi-exclamation-square-fill"></i></span>' + message;
    input.parentElement.insertBefore(tooltip, input.nextSibling);
  }
});
</script>
  <script>
  // Generic numeric contact handler: attach to inputs named contact_number (if present)
  document.addEventListener('DOMContentLoaded', function(){
    function enforceNumeric(el){
      if (!el) return;
      el.setAttribute('inputmode','numeric');
      el.setAttribute('maxlength','11');
      el.addEventListener('keydown', function(e){
        var allowed = [8,9,13,27,46,37,38,39,40];
        if (allowed.indexOf(e.keyCode) !== -1) return;
        if ((e.ctrlKey || e.metaKey) && ['a','c','v','x'].indexOf(e.key.toLowerCase()) !== -1) return;
        if (!/^[0-9]$/.test(e.key)) e.preventDefault();
        if (el.value && el.value.length >= 11 && !['Backspace','Delete'].includes(e.key)) e.preventDefault();
      });
      el.addEventListener('paste', function(e){
        var paste = (e.clipboardData || window.clipboardData).getData('text');
        var digits = paste.replace(/\D+/g,'').slice(0,11);
        e.preventDefault();
        var start = el.selectionStart || 0;
        var end = el.selectionEnd || 0;
        var newVal = (el.value.slice(0,start) + digits + el.value.slice(end)).replace(/\D+/g,'').slice(0,11);
        el.value = newVal;
      });
      el.addEventListener('input', function(){
        var v = el.value.replace(/\D+/g,'').slice(0,11);
        if (el.value !== v) el.value = v;
      });
    }
    document.querySelectorAll('input[name="contact_number"], input.contact-number, #contact_number').forEach(enforceNumeric);
  });
  </script>