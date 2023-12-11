var checkedCommunity = "";
var checkedIntrobadge = "";
$(document).ready(() => {
    $('.community_check').change(() => {
        let count = 0;
        checkedCommunity = "";
        const checkboxes = document.getElementsByClassName('community_check');
        for (let i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                count ++;
                checkedCommunity += checkboxes[i].getAttribute('data-id')+",";
            }
            if (count >= 3) {
                for (let i = 0; i < checkboxes.length; i++) {
                    if (!checkboxes[i].checked) {
                        checkboxes[i].disabled = true;
                    }
                }
                break;
            }
        }
        if (count < 3) {
            $('.community_check').removeAttr('disabled')
        }
    })

    $('.badge_check').change(() => {
        let count = 0;
        checkedIntrobadge = "";
        const checkboxes = document.getElementsByClassName('badge_check');
        for (let i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                count ++;
                checkedIntrobadge += checkboxes[i].getAttribute('data-id')+",";
            }
            if (count >= 5) {
                for (let i = 0; i < checkboxes.length; i++) {
                    if (!checkboxes[i].checked) {
                        checkboxes[i].disabled = true;
                    }
                }
                break;
            }
        }
        if (count < 5) {
            $('.badge_check').removeAttr('disabled')
        }
    })

    //remove data
    $('#removeCustomerWin').on('click', function (event) {
        $('#CustomerID').val($(this).data('id'));
        $('#removeConfirmModal').modal('show');
    });

    $("#modal_show").click(function(){
        dataFormat();
        $('#editModal').modal('show');
    });

    $("#admin_modal").click(function(){
        adminDataFormat();
        $('#editModal').modal('show');
    });

    $('#removeCustomerConfirmBtnWin').on('click', function (event) {
        var CustomerID = $('#CustomerID').val();
        $.ajax({
            url: "/remove_customer",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: CustomerID },
            beforeSend: function(xhr) {
                var token = $('meta[name="csrf-token"]').attr("content");
                if (token) {
                    return xhr.setRequestHeader("X-CSRF-TOKEN", token);
                }
            },
            success: function(response) {
                toastr[response['type']](response['result']);
                if(response['type'] == "success"){
                    setTimeout(() => {
                        window.history.go(0);
                    }, 1500);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr['error'](errorThrown);
            }
        });
    });

})
function updateCustomer(CustomerID){
    $('#edtFlag').val("1");
    $('#CustomerModalLabel').text('編集');
    dataFormat();
    $.ajax({
        url: "/customer_show",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { id: CustomerID },
        beforeSend: function(xhr) {
            var token = $('meta[name="csrf-token"]').attr("content");
            if (token) {
                return xhr.setRequestHeader("X-CSRF-TOKEN", token);
            }
        },
        success: function(response) {
            var result = response.data[0];
            var height = result['height'].split('.');
            const checkboxes = document.querySelectorAll('.community_check');
            const selectedCommunityIds = result['community'];
            checkboxes.forEach(checkbox => {
                const communityId = parseInt(checkbox.getAttribute('data-id'));

                // Check if the communityId is in the selectedCommunityIds array
                if (selectedCommunityIds.includes(communityId)) {
                    checkbox.checked = true;
                }
            });
            checkedCommunity = result['community'];

            const checkboxes1 = document.querySelectorAll('.badge_check');
            const selectedIntrobadgeIds = result['intro_badge'];
            checkboxes1.forEach(checkbox => {
                const IntrobadgeID = parseInt(checkbox.getAttribute('data-id'));

                // Check if the communityId is in the selectedCommunityIds array
                if (selectedIntrobadgeIds.includes(IntrobadgeID)) {
                    checkbox.checked = true;
                }
            });
            checkedIntrobadge = result['intro_badge'];
            $('#nickname').val(result['user_nickname']);
            $('#address').val(result['address']);
            $('#birthday').val(result['birthday']);
            $('#height').val(height);
            $('#bodytype').val(result['body_type']);
            $('#use_purpose').val(result['use_purpose']);
            $('#pay_user').val(result['pay_user']);
            if(result['pay_user'] == "1"){
                $('#hidden_card').attr('style','display:-webkit-box');
                $('#hidden_card').attr('style','margin-top:10px');
            }
            else{
                $('#hidden_card').attr('style','display:none');
            }

            $('#login_info1').attr('style','display:none');
            $('#login_info2').attr('style','display:none');
            $('#pay_date').val(result['pay_date']);
            $('#blood_type').val(result['blood_type']==null?"-1":result['blood_type']);
            $('#education').val(result['education']==null?"-1":result['education']);
            $('#like_rate').val(result['likes_rate']);
            $('#coin').val(result['coin']);
            $('#ciga').val(result['cigarette']);
            $('#alchol').val(result['alcohol']);
            $('#annual_income').val(result['annual_income']==null?"-1":result['annual_income']);
            if(result['identity_state'] == "0" && result['remember_token'] == "1"){
                $('#identity').val("-1");
            }
            else {
                $('#identity').val(result['identity_state']);
            }

            $('#preview-selected-image').attr('src', "https://greemeapp.azurewebsites.net/uploads/"+result['photo1']);
            $('#uid').val(result['id']);
            $('#edittype').val("1");
            $('#editModal').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            toastr['error'](errorThrown);
        }
    });
}

const previewImage = (event) => {
    /**
     * Get the selected files.
     */
    const imageFiles = event.target.files;
    /**
     * Count the number of files selected.
     */
    const imageFilesLength = imageFiles.length;
    /**
     * If at least one image is selected, then proceed to display the preview.
     */
    if (imageFilesLength > 0) {
        /**
         * Get the image path.
         */
        const imageSrc = URL.createObjectURL(imageFiles[0]);
        /**
         * Select the image preview element.
         */
        const imagePreviewElement = document.querySelector("#preview-selected-image");
        /**
         * Assign the path to the image preview element.
         */
        imagePreviewElement.src = imageSrc;
        /**
         * Show the element by changing the display value to "block".
         */
        imagePreviewElement.style.display = "block";
    }
};

function playType(str){
    console.log(str);
    if(str == "1"){
        $('#hidden_card').attr('style','display:-webkit-box');
        $('#hidden_card').attr('style','margin-top:10px');
    }
    else{
        $('#hidden_card').attr('style','display:none');
    }
}

function saveData()
{
    const radioButtons = document.getElementsByName("fav_language");

    // Initialize a variable to store the selected value
    let selectedValue = "";
    var login_type = "";
    var login_id = "";
    // Loop through the radio buttons to find the selected one

    if($('#edittype').val() !="1"){
        // for (const radioButton of radioButtons) {
        //     if (radioButton.checked) {
        //         selectedValue = radioButton.value;
        //         break; // Exit the loop once a selected radio button is found
        //     }
        // }
        // if(selectedValue == "apple_id" && !isValidEmail($('#login_id').val())){
        //     toastr['warning']("メール形式が正しくありません");
        //     return false;
        // }
        if(!$('#login_id').val()){
            toastr['warning']("メール形式が正しくありません");
            return false;
        }
        // if(selectedValue == "apple_id"){
        //     login_type = "0";
        // }
        // else {
            // login_type = "1";
            var trimmedNumber  = $('#login_id').val().replace(/^0+/, '');
            login_id = '+81' + trimmedNumber;
        // }
    }

    var nickName = $('#nickname').val();
    var birthday = $('#birthday').val();
    var address = $('#address').val();
    var height = $('#height').val();
    var bodytype = $('#bodytype').val();
    var use_purpose = $('#use_purpose').val();
    var pay_user = $('#pay_user').val();
    var pay_date = $('#pay_date').val();
    var like_rate = $('#like_rate').val();
    var coin = $('#coin').val();
    var blood_type = $('#blood_type').val();
    var education = $('#education').val();
    var alchol = $('#alchol').val();
    var ciga = $('#ciga').val();
    var annual_income = $('#annual_income').val();
    var identity = $('#identity').val();
    var edittype = $('#edittype').val();
    var uid = $('#uid').val();
    // var login_info = login_type;
    var login_id = login_id;
    let formData = new FormData();
    formData.append('image', fileInput.files[0]);
    formData.append('nickName', nickName);
    formData.append('birthday', birthday);
    formData.append('address', address);
    formData.append('height', height);
    formData.append('bodytype', bodytype);
    formData.append('use_purpose', use_purpose);
    formData.append('pay_user', pay_user);
    formData.append('pay_date', pay_date);
    formData.append('like_rate', like_rate);
    formData.append('coin', coin);
    formData.append('blood_type', blood_type);
    formData.append('education', education);
    formData.append('alchol', alchol);
    formData.append('ciga', ciga);
    formData.append('annual_income', annual_income);
    formData.append('identity', identity);
    formData.append('community', checkedCommunity);
    formData.append('introbadge', checkedIntrobadge);
    formData.append('edittype', edittype);
    formData.append('uid', uid);
    formData.append('login_id', login_id);
    if(customerValidation(nickName, birthday, address, height, bodytype, use_purpose, pay_user, pay_date, like_rate, coin, blood_type, education, alchol, ciga, annual_income, identity, login_id, fileInput.files[0]) == false)
    {
        return;
    }
    $.ajax({
        type:'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
        url: "/register_action",
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function(xhr) {
            var token = $('meta[name="csrf-token"]').attr("content");
            if (token) {
                return xhr.setRequestHeader("X-CSRF-TOKEN", token);
            }
            console.log(token);
        },
        success: (response) => {
            toastr[response['result']](response['msg']);

            if(response['result'] == "success"){
                setTimeout(() => {
                    location.href = "/customer";
                }, 1500);
            }
        },
        error: function(response){
            $('#file-input-error').text(response.responseJSON.message);
        }
    });
}

function customerValidation(nickName, birthday, address, height, bodytype, use_purpose, pay_user, pay_date, ) {
    if(!nickName || !birthday || address=="0" || checkedCommunity  == "" || checkedCommunity.length < 3 || bodytype == "0" || use_purpose =="0" || checkedIntrobadge == "" || checkedIntrobadge.length <4 || !like_rate || pay_user =="-1"  ) {
        toastr['warning']("必要な情報を入力してください。");
        return false;
    }
    if(pay_user == "1" && !pay_date){
        toastr['warning']("必要な情報を入力してください。");
        return false;
    }
    return true;
}

function dataFormat() {
    $("#uid").val(0);
    $("#nickname").val("");
    $("#birthday").val(0);
    $("#address").val(0);
    const checkboxes = document.querySelectorAll('.community_check');
    checkboxes.forEach(checkbox => {
        const communityId = parseInt(checkbox.getAttribute('data-id'));

        // Check if the communityId is in the selectedCommunityIds array
        checkbox.checked = false;
    });

    const checkboxes1 = document.querySelectorAll('.badge_check');
    checkboxes1.forEach(checkbox => {
        const IntrobadgeID = parseInt(checkbox.getAttribute('data-id'));

        // Check if the communityId is in the selectedCommunityIds array
        checkbox.checked = false;
    });
    checkedCommunity = "";
    checkedIntrobadge = "";
    $("#height").val(0);
    $("#bodytype").val(0);
    $("#use_purpose").val(0);
    $("#pay_user").val(-1);
    $("#pay_date").val(0);
    $("#like_rate").val("");
    $("#coin").val("");
    $("#blood_type").val(-1);
    $("#education").val(-1);
    $("#alchol").val(-1);
    $("#ciga").val(-1);
    $("#annual_income").val(-1);
    $('#preview-selected-image').removeAttr('src', "");
    $("#identity").val("");
    $("#edittype").val("0");
    $('#login_info1').attr('style','display:-webkit-box');
    $('#login_info1').attr('style','margin-top:10px');
    $('#login_info2').attr('style','display:-webkit-box');
    $('#login_info2').attr('style','margin-top:10px');
}


 //admin
function updateAdmin(id){
    adminDataFormat();
    $.ajax({
        url: "/get_admin_info",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { id: id },
        beforeSend: function(xhr) {
            var token = $('meta[name="csrf-token"]').attr("content");
            if (token) {
                return xhr.setRequestHeader("X-CSRF-TOKEN", token);
            }
        },
        success: function(response) {
            var result = response.data[0];
            $('#name').val(result['name']);
            $('#email').val(result['email']);
            $('#uid').val(result['id']);
            $('#password').val(result['password']);
            $('#editModal').modal('toggle');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            toastr['error'](errorThrown);
        }
    });
}

function adminDataFormat()
{
    $('#name').val("");
    $('#email').val("");
    $('#password').val("");
    $('#uid').val("");
}

function passFormat()
{
    $('#password').val('123456789');
    $('#passFormat').modal("hide");
}

function isValidEmail(email) {
    // Regular expression for email validation

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    return emailRegex.test(email);
}

function adminSaveData()
{
    var id = $('#uid').val();
    var name = $('#name').val();
    var email = $('#email').val();
    var password = $('#password').val();

    if(adminValidation(name,email,password) == false)
    {
        return;
    }
    $.ajax({
        url: "/admin_save_data",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
        data: { id : id, name: name, email: email, password: password},
        beforeSend: function(xhr) {
            var token = $('meta[name="csrf-token"]').attr("content");
            if (token) {
                return xhr.setRequestHeader("X-CSRF-TOKEN", token);
            }
        },
        success: function(response) {
            toastr[response['type']](response['result']);
            setTimeout(() => {
                location.href = "/admin";
            }, 1500);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            toastr['error'](errorThrown);
        }
    });
}

function adminValidation(name,email,password)
{

    if(name == "")
    {
        toastr['error']("名前を入力してください");
        return false;
    }
    if(email == "")
    {
        toastr['error']("メールを入力してください。");
        return false;
    }

    if (!isValidEmail(email)) {
        toastr['error']("メール形式が正しくありません");
        return false;
    }

    if(password == "")
    {
        toastr['error']("パスワードを入力します。");
        return false;
    }
    return true;
}

function removeAdmin(id)
{
    $("#removeAdminModal").modal("show");
    $("#adminID").val(id);
}

function removeAdminData(){
    var adminID = $("#adminID").val();
    console.log(adminID);

    $.ajax({
        url: "/remove_admin_data",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
        data: { id : adminID},
        beforeSend: function(xhr) {
            var token = $('meta[name="csrf-token"]').attr("content");
            if (token) {
                return xhr.setRequestHeader("X-CSRF-TOKEN", token);
            }
        },
        success: function(response) {
            toastr[response['type']](response['result']);
            setTimeout(() => {
                location.href = "/admin";
            }, 1500);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            toastr['error'](errorThrown);
        }
    });
}
