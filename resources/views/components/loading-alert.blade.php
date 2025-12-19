<script>
    const showLoading = function(title = "", text = "") {
        Swal.fire({
            title,
            text,
            timerProgressBar: true,
            allowEscapeKey: false,
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                swal.showLoading();
            }
        })
    };


    function closeLoading() {
        swal.close();
    }
</script>
