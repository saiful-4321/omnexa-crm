$(document).ready(function () {

    // common modal
    $('body').on("click", '[data-toggle="dynamicModal"]', function (e) {
        e.preventDefault();
        $("#dynamicModal").remove();
        var modal = $(this);
        var url = modal.data("remote") || modal.attr("route") || modal.attr("href");
        var html = $('<div class="modal fade show" id="dynamicModal" tabindex="-1" aria-modal="true"  role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="dynamicModalLabel" aria-hidden="true" style="display: block"></div>');
        $("body").append(html);
        html.modal({
            easein: "flipInX"
        });
        html.load(url);
    });

    // Common Offcanvas Logic
    $('body').on("click", '[data-toggle="commonOffcanvas"]', function (e) {
        e.preventDefault();
        var trigger = $(this);
        var url = trigger.data("remote") || trigger.attr("route") || trigger.attr("href");
        var title = trigger.data("title") || trigger.attr("title") || "Details";

        // Reset and Prep Offcanvas
        $('#commonOffcanvasLabel').text(title);
        $('#commonOffcanvasBody').html('<div class="d-flex justify-content-center align-items-center h-100"><div class="spinner-border text-primary" role="status"></div></div>');
        $('#commonOffcanvasFooter').empty().hide();

        var offcanvasEl = document.getElementById('commonOffcanvas');
        var bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);

        if (!bsOffcanvas) {
            bsOffcanvas = new bootstrap.Offcanvas(offcanvasEl);
        }

        bsOffcanvas.show();

        // Load Content
        $.ajax({
            url: url,
            success: function (response) {
                var $content = $('<div>').html(response);

                // DIRECT INJECTION: Preserve the Form structure!
                // We dump the entire response (including the <form> tag) into the body.
                // We hide the native footer because the form brings its own footer.
                $('#commonOffcanvasBody').html(response);
                $('#commonOffcanvasFooter').hide();

                // Optional: Update title if found in the response
                var $temp = $('<div>').html(response);
                var $modalHeader = $temp.find('.modal-header');
                if ($modalHeader.find('.modal-title').length > 0 && !trigger.data("title")) {
                    $('#commonOffcanvasLabel').text($modalHeader.find('.modal-title').text());
                }
                // Hide header inside body to avoid double headers if desired, 
                // but usually fine to have it or we can hide the outer one.
                // For now, let's trust the design.

                // Re-init plugins if needed (like select2, datepicker) inside offcanvas
                // This might need moving specific init codes to a shared function
                $('#commonOffcanvasBody .select2').select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#commonOffcanvas')
                });
                feather.replace();
            },
            error: function () {
                $('#commonOffcanvasBody').html('<div class="text-danger p-3">Failed to load content.</div>');
            }
        });
    });

    // close modal
    $('body').on('click', '[data-bs-dismiss="modal"]', function () {
        $(this).closest('.modal').modal().hide();
    });

    // feather icons
    feather.replace();

    // select2
    $(".select2").select2({
        placeholder: 'Select an option',
        allowClear: true,
        width: '100%',
        dropdownParent: $('body')
    });
    $(document).on('select2:open', (e) => {
        let $select = $(e.target);
        if (!$select.hasClass('select2-selection--multiple')) {
            let searchField = document.querySelector('.select2-dropdown .select2-search__field');
            if (searchField) {
                searchField.focus();
            }
        }
    });

    // carousel
    $('.carousel').carousel();

    // tooltip
    $('[data-toggle="tooltip"]').tooltip();

    // datepicker
    $('.datepicker').flatpickr({
        altInput: true,
        dateFormat: "YYYY-MM-DD",
        altFormat: "YYYY-MM-DD",
        allowInput: true,
        parseDate: (datestr, format) => {
            return moment(datestr, format, true).toDate();
        },
        formatDate: (date, format, locale) => {
            return moment(date).format(format);
        }
    });

    // datetimepicker
    $('.datetimepicker').flatpickr({
        enableTime: true,
        dateFormat: "YYYY-MM-DD HH:mm:ss",
        altFormat: "YYYY-MM-DD HH:mm:ss",
        allowInput: true,
        parseDate: (datestr, format) => {
            return moment(datestr, format, true).toDate();
        },
        formatDate: (date, format, locale) => {
            return moment(date).format(format);
        }
    });

    // datepicker
    $('.birthdate').flatpickr({
        altInput: true,
        dateFormat: "YYYY-MM-DD",
        altFormat: "YYYY-MM-DD",
        allowInput: true,
        maxDate: "today",
        parseDate: (datestr, format) => {
            return moment(datestr, format, true).toDate();
        },
        formatDate: (date, format, locale) => {
            return moment(date).format(format);
        }
    });


    // datetimepicker
    $('.datetimepicker-date').daterangepicker({
        autoUpdateInput: false,
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'YYYY-MM-DD',
            cancelLabel: 'Clear'
        }
    });
    $('.datetimepicker-date').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
    });
    $('.datetimepicker-date').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });

    // datetimepicker
    $('.datetimepicker-time').daterangepicker({
        autoUpdateInput: false,
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'YYYY-MM-DD',
            cancelLabel: 'Clear'
        }
    });
    $('.datetimepicker-time').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
    });
    $('.datetimepicker-time').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });

    // form common fix - change validation error on change
    $("body").on("keyup change", "input,select", function () {
        $(this).removeClass("is-invalid");
    });

    // Use Event Delegation to handle dynamic content (Offcanvas/Modal)
    // using .off() first to prevent duplicate bindings if common.js is re-executed
    $(document).off('submit', '.dynamicModal').on('submit', '.dynamicModal', function (e) {
        e.preventDefault();

        var form = this; // jQuery 'this' refers to the element
        var formData = new FormData(form);

        // Remove existing error elements
        $(form).find('.invalid-feedback').remove();
        $(form).find('.is-invalid').removeClass('is-invalid');

        // Determine Method (PUT/POST/etc)
        // CRITICAL FIX: Do NOT use the hidden _method field for the Axios/Network call.
        // We must send as POST (form.method) so PHP can parse multipart/form-data.
        // The _method field inside formData will tell Laravel to treat it as PUT/DELETE.
        var method = form.getAttribute('method') || 'POST';

        axios({
            method: method,
            url: form.action,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            onUploadProgress: function (progressEvent) {
                // var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
            }
        })
            .then(function (response) {
                $(form).find('.invalid-feedback').remove();
                $(form).find('.is-invalid').removeClass('is-invalid');

                var responseData = response.data;

                if (responseData.status) {
                    swalWithHtmlAlert('success', responseData.message);
                    setTimeout(function () {
                        // Close Offcanvas
                        var offcanvasEl = document.getElementById('commonOffcanvas');
                        var bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
                        if (bsOffcanvas) bsOffcanvas.hide();

                        if (responseData.redirect) {
                            window.location.href = responseData.redirect;
                        } else {
                            window.location.reload();
                        }
                    }, 1000);
                } else {
                    swalWithHtmlAlert('error', responseData.message);
                    $(form).find('button[type="submit"]').html('<i class="fa fa-refresh"></i> Try again');
                }
            })
            .catch(function (xhr) {
                var res = xhr.response;
                if (res && res.status == 422) {
                    var errors = res.data.errors;
                    for (var name in errors) {
                        if (errors.hasOwnProperty(name)) {
                            var message = errors[name][0]; // Laravel returns array of messages

                            // Handle array field names like generic[0] -> generic[0] or generic.0
                            var dbFieldName = name;

                            // Try to find exact match first
                            var fieldElement = $(form).find('[name="' + dbFieldName + '"]');

                            // If not found, try replacing dot notation with array notation
                            if (fieldElement.length === 0 && name.includes('.')) {
                                // e.g. "permissions.0" -> "permissions[0]"
                                var arrayName = name.replace(/\.(\d+)/g, '[$1]'); // simplistic replacement
                                fieldElement = $(form).find('[name="' + arrayName + '"]');
                            }

                            if (fieldElement.length > 0) {
                                var errorElement = $('<div class="invalid-feedback">' + message + '</div>');
                                fieldElement.parent().append(errorElement);
                                fieldElement.addClass('is-invalid');
                            } else {
                                swalWithHtmlAlert('warning', message);
                            }
                        }
                    }
                } else {
                    var msg = res ? res.statusText : 'Unknown Error';
                    swalWithHtmlAlert('error', msg);
                }

                $(form).find('button[type="submit"]').html('<i class="fa fa-refresh"></i> Try again');
            });
    });

    // Explicitly handle cancel/close for Offcanvas
    $(document).on('click', '[data-bs-dismiss="offcanvas"]', function () {
        var offcanvasEl = document.getElementById('commonOffcanvas');
        var bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
        if (bsOffcanvas) {
            bsOffcanvas.hide();
        }
    });
});

// dark & light mode
document.addEventListener('DOMContentLoaded', function () {
    const currentMode = localStorage.getItem('mode');

    if (currentMode) {
        document.body.setAttribute('data-layout-mode', currentMode);
        if (currentMode === 'dark') {
            document.querySelector('.layout-mode-dark').style.display = "block";
            document.querySelector('.layout-mode-light').style.display = "none";
        }
    }

    document.getElementById('mode-setting-btn').addEventListener('click', function () {
        const mode = localStorage.getItem('mode') === "dark" ? 'light' : 'dark';
        document.body.setAttribute('data-layout-mode', mode);
        localStorage.setItem('mode', mode);

        const darkModeElements = document.querySelector('.layout-mode-dark');
        const lightModeElements = document.querySelector('.layout-mode-light');

        darkModeElements.style.display = mode === 'dark' ? 'block' : 'none';
        lightModeElements.style.display = mode === 'light' ? 'block' : 'none';
    });
});


var copyElements = document.querySelectorAll(".copy");
copyElements.forEach(function (copyElement) {
    copyElement.style.cursor = 'copy';
    copyElement.setAttribute("title", "Copy");

    copyElement.addEventListener("click", function () {

        var textToCopy = "";
        if ((/^(input|textarea)$/i).test(this.tagName.toLowerCase())) {
            textToCopy = this.value;
        } else {
            textToCopy = this.innerText;
        }

        var textarea = document.createElement("textarea");
        textarea.value = textToCopy;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand("copy");
        document.body.removeChild(textarea);

        // Apply copied animation styles
        this.style.background = 'linear-gradient(45deg, #76787e, #082fef)';

        setTimeout(function () {
            copyElement.style.transition = 'transform 0.3s';
            copyElement.style.background = '';
        }, 600);

        copyElement.setAttribute("title", "Copied");
    });
});

// show more/less
function showMore(e) {
    $(e).parent().find('.hidden-text').show();
    $(e).hide();
}
function lessSms(e) {
    $(e).parent().find('.hidden-text').hide();
    $(e).parent().find('.show-more').show();
    $(e).hide();
}


// alert
function swalAlert(type = "success", message = "") {
    Swal.fire({
        title: type.toUpperCase() + ' !',
        text: message,
        icon: type,
        timer: 30000
    });
}

function swalWithHtmlAlert(type = "success", html = '') {
    Swal.fire({
        title: type.toUpperCase() + ' !',
        icon: type,
        html: html,
        timer: 30000
    })
}

function swalConfirmAlert(form) {
    Swal.fire({
        title: 'Are you sure ?',
        text: "You won't be able to revert this !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            form.submit();
        }
        else {
            event.preventDefault();
            return;
        }
    })
}

function deleteConfirm(input) {
    Swal.fire({
        title: 'Are you sure ?',
        text: "You won't be able to revert this !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Delete',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            window.location.href = input.getAttribute("route");
        } else {
            event.preventDefault();
            return;
        }
    })
}
