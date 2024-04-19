<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/home/lib/wow/wow.min.js') }}"></script>
<script src="{{ asset('assets/home/lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('assets/home/lib/waypoints/waypoints.min.js') }}"></script>
<script src="{{ asset('assets/home/lib/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script src="{{ asset('assets/home/js/main.js') }}"></script>

<script>
    $(':radio').change(function() {
        console.log('New star rating: ' + this.value);
    });
</script>

<script>
    let checkPreviewImage = document.getElementById("preview-image");
    let checkPreviewImageName = document.getElementById("preview-name");
    
    function resetValueImage() {
        checkPreviewImage.value = "";
        checkPreviewImageName.value = "";
    }

    if (checkPreviewImage || checkPreviewImageName) {
        window.onload = resetValueImage;
    }

    function validateFileImage(event, fileType) {
        let input = event.target;
        let file = input.files[0];
        let maxSizeInBytes = 1 * 1024 * 1024;
        let extensiFile = fileType;

        let errorMessage;
        let ImageExtension;

        if (fileType === 'jpg') {
            fileTypeRegex = /image\/jpeg/;
            errorMessage = "File harus dalam format JPEG (jpg)!";
        } else if (fileType === 'png') {
            fileTypeRegex = /image\/png/;
            errorMessage = "File harus dalam format PNG (png)!";
        } else if (fileType === 'pdf') {
            fileTypeRegex = /application\/pdf/;
            errorMessage = "File harus dalam format PDF (pdf)!";
        }

        if (!file.type.match(fileTypeRegex)) {
            alert(errorMessage);
            document.getElementById('preview').style.display = 'none';
            input.value = '';
            checkPreviewImage.value = "";
            checkPreviewImageName.value = "";

            return false;
        }

        if (file.size > maxSizeInBytes) {
            alert("Ukuran file harus kurang dari 1 MB!");
            document.getElementById('preview').style.display = 'none';
            input.value = '';
            checkPreviewImage.value = "";
            checkPreviewImageName.value = "";

            return false;
        }

        const reader = new FileReader();

        reader.onload = function(e) {
            const imageData = e.target.result;
            const img = new Image();

            img.onload = function() {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = img.width;
                canvas.height = img.height;

                ctx.fillStyle = '#FFFFFF'; 
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(img, 0, 0);

                if (extensiFile == 'jpg') {
                    ImageExtension = canvas.toDataURL('image/png');
                } else if (extensiFile == 'png') {
                    ImageExtension = canvas.toDataURL('image/jpeg');
                }

                document.getElementById('preview-image').value = ImageExtension;
                document.getElementById('preview-name').value = file.name;
            };
            img.src = imageData;
        };

        reader.readAsDataURL(file);

        return true;
    }
</script>

<script>
    let previewImagePdf = document.getElementById("previewImagePdf");
    let previewNamePdf = document.getElementById("previewNamePdf");
    
    function resetInputValue() {
        previewImagePdf.value = "";
        previewNamePdf.value = "";
    }

    if (previewImagePdf || previewNamePdf) {
        window.onload = resetInputValue;
    }

    function failedValidationPdf() {
        document.getElementById("pdf-preview").style.display = "none";
        previewImagePdf.value = "";
        previewNamePdf.value = "";
    }

    function validateFilePdf(event, fileType) {
        let input = event.target;
        let file = input.files[0];
        let maxSizeInBytes = 1 * 1024 * 1024;
        let fileTypeRegex = /application\/pdf/;

        let submitButton = document.querySelector('.btn[type="submit"]');
        let loadingIndicator = document.getElementById('loadingIndicator');
        let progressBar = document.getElementById('progressBar');
        let progressText = document.getElementById('progressText');

        if (!file.type.match(fileTypeRegex)) {
            alert("File harus dalam format PDF (pdf)!");
            failedValidationPdf();
            input.value = '';
            return false;
        }

        if (file.size > maxSizeInBytes) {
            alert("Ukuran file harus kurang dari " + (maxSizeInBytes / (1024 * 1024)) + " MB!");
            failedValidationPdf();
            input.value = '';
            return false;
        }

        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                failedValidationPdf()
                input.disabled = true;
                submitButton.disabled = true;
                loadingIndicator.style.display = 'block';

                let percentage = 1;
                let interval = 5000 / 100;

                progressBar.style.width = '0%';

                let updateProgress = function() {
                    progressBar.style.width = percentage + '%';
                    progressText.innerText = 'Proses ' + percentage + '%';
                    percentage++;
                    if (percentage <= 100) {
                        setTimeout(updateProgress, interval);
                    } else {
                        input.disabled = false;
                        submitButton.disabled = false;
                        loadingIndicator.style.display = 'none';
                    }
                };

                setTimeout(updateProgress, interval);
            };
            reader.readAsDataURL(input.files[0]);
        }
        
        setTimeout(function() {
            renderPDF(input.files[0]);
        }, 5000);

        return true;
    }
    
    let renderingInProgress = false;
    
    function renderPDF(file) {
        renderingInProgress = true;
        
        let canvas = document.getElementById('pdf-preview');
        canvas.style.display = 'block';
        let ctx = canvas.getContext('2d');
    
        let reader = new FileReader();
        reader.onload = function(event) {
            let typedarray = new Uint8Array(event.target.result);
            pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
                pdf.getPage(1).then(function(page) {
                    let viewport = page.getViewport({scale: 1.5});
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
    
                    let renderContext = {
                        canvasContext: ctx,
                        viewport: viewport
                    };
    
                    let renderTask = page.render(renderContext);
                    
                    renderTask.promise.then(function() {
                        let imageURL;
                        let imageName = file.name.replace('.pdf', '');
                        let pdfToJpg = document.getElementById('pdf-to-jpg');     

                        if (pdfToJpg) {
                            imageURL = canvas.toDataURL('image/jpeg');
                        } else {
                            imageURL = canvas.toDataURL('image/png');
                        }
                        
                        previewImagePdf.value = imageURL;
                        previewNamePdf.value = imageName;
                        renderingInProgress = false;
                    }).catch(function(error) {
                        console.error('Terjadi kesalahan saat merender halaman PDF:', error);
                        renderingInProgress = false;
                    });
                });
            });
        };
        reader.readAsArrayBuffer(file);
    }

    let checkInputPdfToJpg = document.getElementById('pdf-to-jpg');

    if (checkInputPdfToJpg) {
        if (checkInputPdfToJpg.onchange === null) {
            checkInputPdfToJpg.addEventListener('change', function(event) {
                validateAllFile(event, 'pdf');
            });
        }
    }
    
</script>

<script>
    function animasiSubmitPdf(event) {
        const tagInputPdfs = ['pdf-to-jpg', 'pdf-to-png'];
        
        for (let id of tagInputPdfs) {
            const tagInput = document.getElementById(id);
            if (tagInput) {
                if (!tagInput.files || !tagInput.files[0]) {
                    alert('Anda belum memilih file.');
                    event.preventDefault();
                    return;
                }
            }
        }

        document.getElementById('select-primary').style.display = 'none';
        document.getElementById('select-dumy').style.display = 'inline-block';

        document.getElementById('button-primary').style.display = 'none';
        document.getElementById('button-dumy').style.display = 'inline-block';

        let idsToCheck = ['pdf-to-jpg', 'pdf-to-png'];

        idsToCheck.forEach(function(id) {
            setTimeout(function() {
                let input = document.getElementById(id);
                let file = input.files[0];
                let submitButton = document.querySelector('.btn[type="submit"]');
                let loadingIndicator = document.getElementById('loadingIndicator');
                let progressBar = document.getElementById('progressBar');
                let progressText = document.getElementById('progressText');

                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        input.disabled = true;
                        submitButton.disabled = true;
                        loadingIndicator.style.display = 'block';

                        let percentage = 1;
                        let interval = 10000 / 100;

                        progressBar.style.width = '0%';

                        let updateProgress = function() {
                            progressBar.style.width = percentage + '%';
                            progressText.innerText = 'Proses ' + percentage + '%';
                            percentage++;
                            if (percentage <= 100) {
                                setTimeout(updateProgress, interval);
                            } else {
                                input.disabled = false;
                                submitButton.disabled = false;
                                loadingIndicator.style.display = 'none';
                            }
                        };

                        setTimeout(updateProgress, interval);
                    };
                    reader.readAsDataURL(file);
                }
            }, 2000);
        });
    }
</script>

<script>
    function startProcess(event) {
        const tagInputIds = ['jpg-to-png', 'png-to-jpg', 'jpg-to-pdf', 'pdf-to-jpg', 'png-to-pdf'];
        
        for (let id of tagInputIds) {
            const tagInput = document.getElementById(id);
            if (tagInput) {
                if (!tagInput.files || !tagInput.files[0]) {
                    alert('Anda belum memilih file.');
                    event.preventDefault();
                    return;
                }
            }
        }

        document.getElementById('loadingIndicator').style.display = 'block';
        disableFileInput();
    }

    function disableFileInput() {
        document.getElementById('select-primary').style.display = 'none';
        document.getElementById('select-dumy').style.display = 'inline-block';

        document.getElementById('button-primary').style.display = 'none';
        document.getElementById('button-dumy').style.display = 'inline-block';

        let progressBar = document.getElementById('progressBar');
        let progressText = document.getElementById('progressText');

        let percentage = 1;
        let interval = 90000 / 100;

        function updateProgress() {
            progressBar.style.width = percentage + '%';
            progressText.innerText = 'Proses ' + percentage + '%';
            percentage++;
            if (percentage <= 100) {
                setTimeout(updateProgress, interval);
            }
        }

        updateProgress();
    }
</script>

<script>
    const ownership = localStorage.getItem('ownership');
    const forms = ['reply-jpg-to-png', 'reply-png-to-jpg', 'reply-jpg-to-pdf', 'reply-pdf-to-jpg', 'reply-png-to-pdf'];

    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (ownership && form) {
            form.action = `/${formId.replace('reply-', '')}/${ownership}`;
        }
    });
</script>

<script>
    let currentImage = null;

    function previewImage(event) {
        let input = event.target;
        let submitButton = document.querySelector('.btn[type="submit"]');
        let loadingIndicator = document.getElementById('loadingIndicator');
        let progressBar = document.getElementById('progressBar');
        let progressText = document.getElementById('progressText');

        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                let previewImage = document.getElementById('preview');
                previewImage.style.display = 'none';

                input.disabled = true;
                submitButton.disabled = true;
                loadingIndicator.style.display = 'block';

                let percentage = 1;
                let interval = 5000 / 100;

                progressBar.style.width = '0%';

                let updateProgress = function() {
                    progressBar.style.width = percentage + '%';
                    progressText.innerText = 'Proses ' + percentage + '%';
                    percentage++;
                    if (percentage <= 100) {
                        setTimeout(updateProgress, interval);
                    } else {
                        previewImage.src = e.target.result;
                        previewImage.style.display = 'block';
                        currentImage = e.target.result;

                        input.disabled = false;
                        submitButton.disabled = false;
                        loadingIndicator.style.display = 'none';
                    }
                };

                setTimeout(updateProgress, interval);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<script>
    setTimeout(function() {
        const checkOwnershipFile = localStorage.getItem('ownership');
        const title = document.getElementById('title-page-convert');

        if (checkOwnershipFile) {
            axios.post(`/delete-convert/${checkOwnershipFile}/${title.textContent}`, {
                _method: 'DELETE'
            })
            .then(response => {
                localStorage.removeItem('ownership');
                window.location.href = '/';
            })
            .catch(error => {
                localStorage.removeItem('ownership');
                window.location.href = '/';
            });
        }
    }, 600000);
</script>

<script>
    setTimeout(function() {
            axios.post(`/delete-convert/10-minute`, {
                _method: 'DELETE'
            })
            .then(response => {
            })
            .catch(error => {
            });
    }, 5000);

</script>