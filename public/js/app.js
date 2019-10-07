$(document).ready(function() {
$.ajaxSetup({
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})
$(document).on('click', '.btn-destroy', function(e){
e.preventDefault();
let url = $(this).data("id");
    Swal.fire({
        title: "Kamu yakin?",
        text: "Kamu tidak akan bisa membatalkan proses ini!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yap, lanjutkan!',
        cancelButtonText: 'Batal',
        showCloseButton: true
    }).then((result) => {
      if (result.value) {
        $.ajax({
                url : url,
                type : "POST",
                data : {"_method" : "DELETE"},
                success: function(){
                  Swal.fire({
                      title: "Berhasil!",
                      text : "Data telah dihapus. \n Klik OK untuk merefresh",
                      type : "success",
                  }).then(function(){ 
                      location.reload();
                  })
              }
            })
          }
    })
})
})


