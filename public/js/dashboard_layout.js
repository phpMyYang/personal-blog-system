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
                    "emptyTable": "<div style='text-align: center !important; padding: 2rem 0 !important; font-style: italic;'>No comments found.</div>",
                    "zeroRecords": "<div style='text-align: center !important; padding: 2rem 0 !important; font-style: italic;'>No matching comments found.</div>"
                }
            });
        }
    } catch (e) {
        console.error("DataTables initialization failed:", e);
    }

    try {
        if ($('#categoriesTable').length) { 
            $('#categoriesTable').DataTable({
                "pageLength": 10,
                "order": [], // Walang initial sorting
                "language": {
                    "emptyTable": "<div style='text-align: center !important; padding: 2rem 0 !important; font-style: italic;'>No categories created yet.</div>",
                    "zeroRecords": "<div style='text-align: center !important; padding: 2rem 0 !important; font-style: italic;'>No matching categories found.</div>"
                }
            });
        }
    } catch (e) {
        console.error("DataTables initialization failed:", e);
    }
});