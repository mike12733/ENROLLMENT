// Form validation and interactive features
document.addEventListener('DOMContentLoaded', function() {
    
    // Registration form validation
    const registrationForm = document.getElementById('registrationForm');
    if (registrationForm) {
        registrationForm.addEventListener('submit', function(e) {
            if (!validateRegistrationForm()) {
                e.preventDefault();
            }
        });
    }

    // Login form validation
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            if (!validateLoginForm()) {
                e.preventDefault();
            }
        });
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });

    // Dynamic dashboard count animation
    animateCounters();
});

function validateRegistrationForm() {
    const form = document.getElementById('registrationForm');
    const errors = [];
    
    // Get form elements
    const firstName = form.querySelector('[name="first_name"]');
    const lastName = form.querySelector('[name="last_name"]');
    const dateOfBirth = form.querySelector('[name="date_of_birth"]');
    const gender = form.querySelector('[name="gender"]');
    const email = form.querySelector('[name="email"]');
    const phone = form.querySelector('[name="phone"]');
    const studentId = form.querySelector('[name="student_id"]');
    const gradeLevel = form.querySelector('[name="grade_level"]');
    const program = form.querySelector('[name="program"]');

    // Clear previous error styles
    clearErrorStyles(form);

    // Validate required fields
    if (!firstName.value.trim()) {
        errors.push({field: firstName, message: 'First name is required'});
    }

    if (!lastName.value.trim()) {
        errors.push({field: lastName, message: 'Last name is required'});
    }

    if (!dateOfBirth.value) {
        errors.push({field: dateOfBirth, message: 'Date of birth is required'});
    } else {
        // Check if date is not in the future and student is not too young
        const birthDate = new Date(dateOfBirth.value);
        const today = new Date();
        const age = today.getFullYear() - birthDate.getFullYear();
        
        if (birthDate > today) {
            errors.push({field: dateOfBirth, message: 'Date of birth cannot be in the future'});
        } else if (age < 10 || age > 25) {
            errors.push({field: dateOfBirth, message: 'Age must be between 10 and 25 years'});
        }
    }

    if (!gender.value) {
        errors.push({field: gender, message: 'Gender is required'});
    }

    if (!email.value.trim()) {
        errors.push({field: email, message: 'Email is required'});
    } else if (!isValidEmail(email.value)) {
        errors.push({field: email, message: 'Please enter a valid email address'});
    }

    if (!phone.value.trim()) {
        errors.push({field: phone, message: 'Phone number is required'});
    } else if (!isValidPhone(phone.value)) {
        errors.push({field: phone, message: 'Please enter a valid phone number'});
    }

    if (!studentId.value.trim()) {
        errors.push({field: studentId, message: 'Student ID is required'});
    } else if (studentId.value.length < 6) {
        errors.push({field: studentId, message: 'Student ID must be at least 6 characters'});
    }

    if (!gradeLevel.value) {
        errors.push({field: gradeLevel, message: 'Grade level is required'});
    }

    if (!program.value) {
        errors.push({field: program, message: 'Program is required'});
    }

    // Display errors
    if (errors.length > 0) {
        displayErrors(errors);
        return false;
    }

    // Add loading state to submit button
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<span class="loading"></span> Registering...';
    submitBtn.disabled = true;

    return true;
}

function validateLoginForm() {
    const form = document.getElementById('loginForm');
    const errors = [];
    
    const username = form.querySelector('[name="username"]');
    const password = form.querySelector('[name="password"]');

    clearErrorStyles(form);

    if (!username.value.trim()) {
        errors.push({field: username, message: 'Username is required'});
    }

    if (!password.value.trim()) {
        errors.push({field: password, message: 'Password is required'});
    }

    if (errors.length > 0) {
        displayErrors(errors);
        return false;
    }

    // Add loading state to submit button
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<span class="loading"></span> Logging in...';
    submitBtn.disabled = true;

    return true;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidPhone(phone) {
    const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
    return phoneRegex.test(phone);
}

function clearErrorStyles(form) {
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.style.borderColor = '#e1e5e9';
        const errorMsg = input.parentNode.querySelector('.error-message');
        if (errorMsg) {
            errorMsg.remove();
        }
    });
}

function displayErrors(errors) {
    errors.forEach(error => {
        // Add error styling to field
        error.field.style.borderColor = '#dc3545';
        
        // Add error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.style.color = '#dc3545';
        errorDiv.style.fontSize = '0.875rem';
        errorDiv.style.marginTop = '0.25rem';
        errorDiv.textContent = error.message;
        
        error.field.parentNode.appendChild(errorDiv);
    });

    // Scroll to first error
    if (errors.length > 0) {
        errors[0].field.scrollIntoView({ behavior: 'smooth', block: 'center' });
        errors[0].field.focus();
    }
}

function animateCounters() {
    const counters = document.querySelectorAll('.tile-number');
    
    counters.forEach(counter => {
        const target = parseInt(counter.textContent);
        let current = 0;
        const increment = target / 30; // 30 frames for animation
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            counter.textContent = Math.floor(current);
        }, 50);
    });
}

// Auto-generate Student ID
function generateStudentId() {
    const year = new Date().getFullYear();
    const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
    return `${year}${random}`;
}

// Set up auto-generation button if exists
document.addEventListener('DOMContentLoaded', function() {
    const generateBtn = document.getElementById('generateStudentId');
    const studentIdInput = document.querySelector('[name="student_id"]');
    
    if (generateBtn && studentIdInput) {
        generateBtn.addEventListener('click', function() {
            studentIdInput.value = generateStudentId();
        });
    }
});

// Real-time form validation feedback
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input[required], select[required]');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.style.borderColor === 'rgb(220, 53, 69)') { // If has error
                validateField(this);
            }
        });
    });
});

function validateField(field) {
    const errorMsg = field.parentNode.querySelector('.error-message');
    if (errorMsg) {
        errorMsg.remove();
    }
    
    if (field.hasAttribute('required') && !field.value.trim()) {
        field.style.borderColor = '#dc3545';
        showFieldError(field, 'This field is required');
        return false;
    }
    
    if (field.type === 'email' && field.value && !isValidEmail(field.value)) {
        field.style.borderColor = '#dc3545';
        showFieldError(field, 'Please enter a valid email address');
        return false;
    }
    
    // Field is valid
    field.style.borderColor = '#28a745';
    return true;
}

function showFieldError(field, message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.color = '#dc3545';
    errorDiv.style.fontSize = '0.875rem';
    errorDiv.style.marginTop = '0.25rem';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

// Smooth scroll for navigation links
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('a[href^="#"]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Mobile menu toggle (if needed)
function toggleMobileMenu() {
    const navMenu = document.querySelector('.nav-menu');
    navMenu.classList.toggle('active');
}