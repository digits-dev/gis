function handleExportGenerator(filters) {

    $.ajax({
        url: "/admin/collect_token_histories/export_collected_token_history",
        type: "POST",
        data: {
            filters: filters
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            Swal.fire({
                icon: "info",
                title: "Generating Collect Token Export",
                html: `
                    <img src="https://cdn-icons-png.flaticon.com/128/4911/4911806.png" width="50"/>
                    <p style="margin-top: 5px;">Please wait...</p>
                `,
                showCancelButton: false,
                allowOutsideClick: false,
                didOpen: (popup) => {
                    Swal.showLoading(); 
                }
            });
        },
        success: function (response) {
            if(response.success == true){
                Swal.fire({
                    icon: "success",
                    title: response.message,
                    html: `Downloading, Please wait again...`,
                    allowOutsideClick: false,
                    timer: 3000,
                    didOpen: () => Swal.showLoading()
                });

                setTimeout(() => {
                    let file_name = response.file_name;
                
                    // Automatically trigger the file download
                    window.location.href = `/admin/collect_token_histories/download_generated_collected_token_history?file_name=${encodeURIComponent(file_name)}`;
                }, 3000);
            }
        },
        error: function (xhr, error) {
            Swal.close();
            Swal.fire({
                title: "Failed to generate collect token export!",
                html: xhr.responseJSON?.error || "Something went wrong!",
                icon: "error",
                timer: 3000
            });
        }
    });
}
