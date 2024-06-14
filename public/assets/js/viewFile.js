function viewFile(url, ext, title) {
    $('#viewFileModal').modal('show');
    if (ext == 'image') {
        $('.modal-title').html(title);
        $('.viewFile-modal').html('<img class="img-fluid mx-auto w-100 d-block" src="' + url + '" alt="Image">');
    } else if (ext == 'pdf') {
        $('.modal-title').html(title);
        $('.viewFile-modal').html('<iframe class="mx-auto w-100" src="' + url + '" style="width: 100%; height: 80vh;"></iframe>');
    }
}

// viewFile
$(document).on('click','.view-file',function(){
    let file = $(this).attr('data-file');
    let ext = $(this).attr('data-ext');
    let title = $(this).attr('data-title');
    viewFile(file,ext,title);
})