document.addEventListener('DOMContentLoaded', function () {
    
    // Hanapin lahat ng file drop areas
    const fileDropAreas = document.querySelectorAll('.file-drop-area');

    fileDropAreas.forEach(dropArea => {
        const fileInput = dropArea.querySelector('.file-drop-input');
        const fileMessage = dropArea.querySelector('.file-drop-message');
        const filePreview = dropArea.querySelector('.file-drop-preview');
        const fileSubtext = dropArea.querySelector('.file-drop-subtext');
        const fileIcon = dropArea.querySelector('.file-drop-icon');

        // 1. Ipakita ang filename kapag may pinili (via click)
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                // Ipakita ang pangalan ng file
                filePreview.textContent = `Selected file: ${fileInput.files[0].name}`;
                // Itago ang original message
                fileMessage.style.display = 'none'; 
                fileSubtext.style.display = 'none';
                fileIcon.style.display = 'none';
            }
        });

        // 2. Drag and Drop Events
        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('is-dragover');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('is-dragover');
        });

        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.classList.remove('is-dragover');

            // Kunin ang files at ilagay sa hidden input
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;

                // Manually trigger ang 'change' event para gumana ang Step 1
                const changeEvent = new Event('change');
                fileInput.dispatchEvent(changeEvent);
            }
        });
    });

    // --- I-ACTIVATE ANG BOOTSTRAP TOOLTIPS ---
    // Ito ay para gumana ang "Edit" at "Delete" tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    try {
        $('#postsTable').DataTable({
            "pageLength": 10,

            "language": {
                "emptyTable": "<div style='text-align: center !important; font-style: italic;'>You haven't created any posts yet.</div>",
                "zeroRecords": "<div style='text-align: center !important; font-style: italic;'>No matching posts found. Try a different search.</div>"
            }
        });
    } catch (e) {
        console.error("DataTables initialization failed:", e);
    }

    const userDropdownEl = document.getElementById('navbarDropdown');

    if (userDropdownEl && typeof bootstrap !== 'undefined') {
        // Gumawa ng bagong instance ng Dropdown
        const userDropdown = new bootstrap.Dropdown(userDropdownEl);

        // Mag-add ng event listener sa button para i-toggle ito
        userDropdownEl.addEventListener('click', function (event) {
            event.preventDefault(); 
            userDropdown.toggle();
        });
    }

    const avatarInput = document.getElementById('avatarInput');
    const avatarFileNameSpan = document.getElementById('avatarFileName');

    if (avatarInput && avatarFileNameSpan) {
        avatarInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                // Ipakita ang pangalan ng file
                avatarFileNameSpan.textContent = `File selected: ${this.files[0].name}`;
            } else {
                // Burahin kung walang pinili
                avatarFileNameSpan.textContent = '';
            }
        });
    }

    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: 'textarea.tinymce-editor',
            height: 500,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'media', 'table', 
                'code', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                     'bold italic underline | alignleft aligncenter alignright | ' +
                     'bullist numlist outdent indent | link image media | code',
            content_style: 'body { font-family:Poppins,sans-serif; font-size:16px }'
        });
    }

    const postForm = document.getElementById('postForm');
    if (postForm) {
        postForm.addEventListener('submit', function(e) {
            // Suriin kung ang TinyMCE ay UMIIRAL at may active editor
            if (typeof tinymce !== 'undefined' && tinymce.activeEditor) {
                // I-trigger ang save
                tinymce.triggerSave(); 
            }
        });
    }

    try {
        if ($('#commentsTable').length) { 
            $('#commentsTable').DataTable({
                "pageLength": 10,
                // Pagsunud-sunurin base sa Status (Pending muna)
                "order": [[ 3, 'asc' ]], 
                "language": {
                    "emptyTable": "<div style='text-align: center !important; font-style: italic;'>No comments found.</div>",
                    "zeroRecords": "<div style='text-align: center !important; font-style: italic;'>No matching comments found.</div>"
                }
            });
        }
    } catch (e) {
        console.error("DataTables initialization failed:", e);
    }

    const editCategoryModal = document.getElementById('editCategoryModal');
    const categoriesTable = document.getElementById('categoriesTable');

    if (categoriesTable && editCategoryModal) {

        categoriesTable.addEventListener('click', function(event) {

            const clickedElement = event.target;
            const editButton = clickedElement.closest('.edit-btn'); 

            if (!editButton) {
                return; 
            }

            event.preventDefault();

            const categoryName = editButton.getAttribute('data-name');
            const categorySlug = editButton.getAttribute('data-slug');
            const updateUrl = editButton.getAttribute('data-update-url');

            const modalForm = document.getElementById('editCategoryForm');
            const modalNameInput = document.getElementById('edit_name');
            const modalSlugInput = document.getElementById('edit_slug');

            if (modalForm) modalForm.setAttribute('action', updateUrl);
            if (modalNameInput) modalNameInput.value = categoryName;
            if (modalSlugInput) modalSlugInput.value = categorySlug;

            const modalInstance = new bootstrap.Modal(editCategoryModal);
            modalInstance.show();
        });
    }

    try {
        if ($('#categoriesTable').length) { 
            $('#categoriesTable').DataTable({
                "pageLength": 10,
                "order": [],
                "autoWidth": false, 
                "language": {
                    "emptyTable": "<div style='text-align: center !important; font-style: italic;'>No categories created yet.</div>",
                    "zeroRecords": "<div style='text-align: center !important; font-style: italic;'>No matching categories found.</div>"
                }
            });
        }
    } catch (e) {
        console.error("DataTables initialization failed:", e);
    }
    
    const postsCtx = document.getElementById('postsChart');
    const commentsCtx = document.getElementById('commentsChart');

    if (postsCtx && commentsCtx && typeof Chart !== 'undefined') {
        try {
            const style = getComputedStyle(document.body);
            const colorPrimary = style.getPropertyValue('--color-primary').trim();
            const colorAccent = style.getPropertyValue('--color-accent').trim();
            const colorTextLight = style.getPropertyValue('--color-text-light').trim();
            const postDataString = postsCtx.getAttribute('data-chart-data');
            const postData = JSON.parse(postDataString);

            new Chart(postsCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Posts',
                        data: postData, 
                        backgroundColor: colorPrimary,
                        borderColor: colorPrimary,
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { 
                                precision: 0 
                            }
                        }
                    }
                }
            });

            const commentDataString = commentsCtx.getAttribute('data-chart-data');
            const commentData = JSON.parse(commentDataString);

            new Chart(commentsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Approved', 'Pending'],
                    datasets: [{
                        data: commentData,
                        backgroundColor: [
                            colorAccent, 
                            colorTextLight 
                        ],
                        borderColor: '#fff',
                        borderWidth: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

        } catch (e) {
            console.error("Chart.js initialization failed:", e);
        }
    }

    const passwordToggleIcons = document.querySelectorAll('.password-toggle-icon');

    if (passwordToggleIcons.length > 0) {
        passwordToggleIcons.forEach(iconElement => {
            
            iconElement.addEventListener('click', function () {
                const icon = this.querySelector('i');
                const inputGroup = this.closest('.input-group');
                const input = inputGroup.querySelector('input');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                }
            });

        });
    }
});