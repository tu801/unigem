$('textarea#description-editor').tinymce({
    height: 300,
    menubar: true,
    toolbar_sticky: true,
    plugins: [
        'advlist autolink lists link image media charmap print preview anchor',
        'searchreplace visualblocks code fullscreen hr emoticons',
        'insertdatetime media table paste help imagetools wordcount',
    ],
    toolbar: 'undo redo | formatselect | bold italic blockquote forecolor backcolor | alignleft aligncenter alignright alignjustify hr emoticons | bullist numlist outdent indent | link image media | removeformat | code | help',
    image_caption: true,
    image_advtab: true,
    image_uploadtab: true,
    images_upload_credentials: true,
    images_upload_handler: function (blobInfo, success, failure, progress) {
        var xhr, formData;
        var token = $('#token').val();
        var token_key = $('#token-key').val();

        let uploadUrl = base_url + "/acp/attach/mceUpload";
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', uploadUrl);

        xhr.upload.onprogress = function (e) {
            progress(e.loaded / e.total * 100);
        };

        xhr.onload = function () {
            var json;

            if (xhr.status < 200 || xhr.status >= 300) {
                failure('HTTP Error: ' + xhr.status);
                return;
            }

            json = JSON.parse(xhr.responseText);

            if (json.error) {
                failure(json.errMess);
                return;
            }
            if (!json || typeof json.location != 'string') {
                failure('Invalid JSON: ' + xhr.responseText);
                return;
            }

            success(json.location);
        };

        xhr.onerror = function () {
            failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
        };

        formData = new FormData();
        formData.append(token, token_key);
        formData.append('file', blobInfo.blob(), blobInfo.filename());

        xhr.send(formData);
    },
    relative_urls: false,
    remove_script_host: false,
    rel_list: [
        {title: 'No Referrer', value: 'noreferrer'},
        {title: 'No Follow', value: 'nofollow'},
        {title: 'External Link', value: 'external'}
    ]
});