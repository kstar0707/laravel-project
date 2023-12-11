    // File upload form
    var btnUpload = $("#image"),
		btnOuter = $(".button_outer");
	btnUpload.on("change", function(e){
		var ext = btnUpload.val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			$(".error_msg").text("Not an Image...");
		} else {
			$(".error_msg").text("");
			btnOuter.addClass("file_uploading");
			setTimeout(function(){
				btnOuter.addClass("file_uploaded");
			},3000);
			var uploadedFile = URL.createObjectURL(e.target.files[0]);
			setTimeout(function(){
                $('#uploaded_view').css('opacity', '1');
                $("#uploaded_view").children('img').remove();
				$("#uploaded_view").append('<img src="'+uploadedFile+'" />').addClass("show");
			},3500);
		}
	});
	$(".file_remove").on("click", function(e){
		$("#uploaded_view").removeClass("show");
        $('#uploaded_view').css('opacity', '0');
		$("#uploaded_view").find("img").remove();
		btnOuter.removeClass("file_uploading");
		btnOuter.removeClass("file_uploaded");
	});

    $('#received_user').select2();


    // Residence
    $('.newResidenceModalBtn').on('click', function () {
        $('#edtFlag').val("0");
        $('#ResidenceModalLabel').text("追加");
    });

    $('#saveNewResidence').click(function () {
        var residenceName = $('#edtResidenceName').val();
        if(residenceName == '') {
            toastr['warning']("居住地を入力してください");
        } else {
            if($('#edtFlag').val() == "0") {
                $.ajax({
                    url: "/residence_store",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { data: residenceName },
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
            } else {
                var residenceId = $('#edtResidenceId').val();
                $.ajax({
                    url: "/residence_update",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { name: residenceName, id: residenceId },
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
            }
        }
    });

    $('.updateResidence').on('click', function (event) {
        $('#edtFlag').val("1");
        $('#ResidenceModalLabel').text('編集');
        $('#edtResidenceId').val($(this).data('id'));
        $('#edtResidenceName').val($(this).data('val'));
        $('#addResidenceModal').modal('toggle');
    });

    $('.removeResidence').on('click', function (event) {
        $('#residenceID').val($(this).data('id'));
        $('#removeConfirmModal').modal('toggle');
    });

    $('#removeResidenceConfirmBtn').on('click', function (event) {
        var residenceId = $('#residenceID').val();
        $.ajax({
            url: "/residence_remove",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: residenceId },
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

    // body type
    $('.newBodytypeModalBtn').on('click', function () {
        $('#edtFlag').val("0");
        $('#BodytypeModalLabel').text("追加");
    });

    $('#saveNewBodytype').on('click', function (event) {
        event.stopPropagation();
        var bodyType = $('#edtBodytypeName').val();
        if(bodyType == '') {
            toastr['warning']("体型を選択します");
        } else {
            if($('#edtFlag').val() == "0") {
                $.ajax({
                    url: "/bodytype_store",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { data: bodyType },
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
            } else {
                var bodyTypeID = $('#edtBodytypeId').val();
                $.ajax({
                    url: "/bodytype_update",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { type: bodyType, id: bodyTypeID },
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
            }
        }
    });

    $('.updatebodytype').on('click', function (event) {
        $('#edtFlag').val("1");
        $('#BodytypeModalLabel').text('編集');
        $('#edtBodytypeId').val($(this).data('id'));
        $('#edtBodytypeName').val($(this).data('val'));
        $('#addBodytypeModal').modal('toggle');
    });

    $('.removebodytype').on('click', function (event) {
        $('#bodytypeID').val($(this).data('id'));
        $('#removeConfirmModal').modal('toggle');
    });

    $('#removeBodytypeConfirmBtn').on('click', function (event) {
        var bodyTypeId = $('#bodytypeID').val();
        $.ajax({
            url: "/bodytype_remove",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: bodyTypeId },
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

    // user purpose
    $('.newUsepurposeModalBtn').on('click', function () {
        $('#edtFlag').val("0");
        $('#UsepurposeModalLabel').text("追加");
    });

    $('#saveNewUsepurpose').on('click', function (event) {
        event.stopPropagation();
        var usepurpose = $('#edtUsepurposeName').val();
        if(usepurpose == '') {
            toastr['warning']("利用目的を選択してください");
        } else {
            if($('#edtFlag').val() == "0") {
                $.ajax({
                    url: "/usepurpose_store",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { data: usepurpose },
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
            } else {
                var usepurposeID = $('#edtUsepurposeId').val();
                $.ajax({
                    url: "/usepurpose_update",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { type: usepurpose, id: usepurposeID },
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
            }
        }
    });

    $('.updateUsepurpose').on('click', function (event) {
        $('#edtFlag').val("1");
        $('#UsepurposeModalLabel').text('編集');
        $('#edtUsepurposeId').val($(this).data('id'));
        $('#edtUsepurposeName').val($(this).data('val'));
        $('#addUsepurposeModal').modal('toggle');
    });

    $('.removeUsepurpose').on('click', function (event) {
        $('#usepurposeID').val($(this).data('id'));
        $('#removeConfirmModal').modal('toggle');
    });

    $('#removeUsepurposeConfirmBtn').on('click', function (event) {
        var usepurposeID = $('#usepurposeID').val();
        $.ajax({
            url: "/usepurpose_remove",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: usepurposeID },
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

    // intro badge
    $('.newIntrobadgeModalBtn').on('click', function () {
        $('#edtFlag').val("0");
        $('#IntrobadgeModalLabel').text("追加");
    });

    $('#saveNewIntrobadge').on('click', function (event) {
        event.stopPropagation();
        var introbadgeText = $('#edtIntrobadgeName').val();
        var introbadgeColor = $('#edtIntrobadgeColor').val();
        if(introbadgeText == '') {
            toastr['warning']("タグのテキストを入力してください");
        } else if(introbadgeColor == '') {
            toastr['warning']("タグの色を選択してください");
        } else {
            if($('#edtFlag').val() == "0") {
                $.ajax({
                    url: "/introbadge_store",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { text: introbadgeText, color: introbadgeColor },
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
            } else {
                var introbadgeID = $('#edtIntrobadgeId').val();
                $.ajax({
                    url: "/introbadge_update",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { text: introbadgeText, color: introbadgeColor, id: introbadgeID },
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
            }
        }
    });

    $('.updateIntrobadge').on('click', function (event) {
        $('#edtFlag').val("1");
        $('#IntrobadgeModalLabel').text('編集');
        $('#edtIntrobadgeId').val($(this).data('id'));
        $('#edtIntrobadgeName').val($(this).data('text'));
        $('#edtIntrobadgeColor').val($(this).data('color'));
        $('#addIntrobadgeModal').modal('toggle');
    });

    $('.removeIntrobadge').on('click', function (event) {
        $('#IntrobadgeID').val($(this).data('id'));
        $('#removeConfirmModal').modal('toggle');
    });

    $('#removeIntrobadgeConfirmBtn').on('click', function (event) {
        var IntrobadgeID = $('#IntrobadgeID').val();
        $.ajax({
            url: "/introbadge_remove",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: IntrobadgeID },
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

    // paid plan type
    $('.newPaidPlanTypeModalBtn').on('click', function () {
        $('#edtFlag').val("0");
        $('#PaidPlanTypeModalLabel').text("追加");
    });

    $('#saveNewPaidPlanType').on('click', function (event) {
        event.stopPropagation();
        var paidplantypename = $('#edtPaidPlanTypeName').val();
        var paidplantypeprice = $('#edtPaidPlanTypePrice').val();
        if(paidplantypename == '') {
            toastr['warning']("有料プランのタイプ名を入力してください");
        } else if(paidplantypeprice == '') {
            toastr['warning']("有料プランのタイプ価格を入力してください");
        } else {
            if($('#edtFlag').val() == "0") {
                $.ajax({
                    url: "/paidplantype_store",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { type: paidplantypename, price: paidplantypeprice },
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
            } else {
                var PaidplantypeID = $('#edtPaidPlanTypeId').val();
                $.ajax({
                    url: "/paidplantype_update",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { type: paidplantypename, price: paidplantypeprice, id: PaidplantypeID },
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
            }
        }
    });

    $('.updatePaidPlanType').on('click', function (event) {
        $('#edtFlag').val("1");
        $('#PaidPlanTypeModalLabel').text('編集');
        $('#edtPaidPlanTypeId').val($(this).data('id'));
        $('#edtPaidPlanTypeName').val($(this).data('type'));
        $('#edtPaidPlanTypePrice').val($(this).data('price'));
        $('#addPaidPlanTypeModal').modal('toggle');
    });

    $('.removePaidPlanType').on('click', function (event) {
        $('#PaidPlanTypeID').val($(this).data('id'));
        $('#removeConfirmModal').modal('toggle');
    });

    $('#removePaidPlanTypeConfirmBtn').on('click', function (event) {
        var PaidplantypeID = $('#PaidPlanTypeID').val();
        $.ajax({
            url: "/paidplantype_remove",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: PaidplantypeID },
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

    // customer
    $('.newCustomerModalBtn').on('click', function () {
        $('#edtFlag').val("0");
        $('#CustomerModalLabel').text("追加");
        $('input').val("");
        if (!$(".additional").hasClass("d-none")) $(".additional").addClass("d-none");
    });

    $('#customer-data').submit(function () {
        var formData = new FormData(this);
        $.ajax({
            type:'POST',
            url: "/customer_store",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function(xhr) {
                var token = $('meta[name="csrf-token"]').attr("content");
                if (token) {
                    return xhr.setRequestHeader("X-CSRF-TOKEN", token);
                }
            },
            cache:false,
            contentType: false,
            processData: false,
            success: function(response) {
                toastr[response['type']](response['result']);
                if(response['type'] == "success"){
                    setTimeout(() => {
                        window.history.go(0);
                    }, 1500);
                }
            },
            error: function(data){
                console.log(data);
            }
        });
    });

    $('.removeCustomer').on('click', function (event) {
        $('#CustomerID').val($(this).data('id'));
        $('#removeConfirmModal').modal('toggle');
    });

    $('#removeCustomerConfirmBtn').on('click', function (event) {
        var CustomerID = $('#CustomerID').val();
        $.ajax({
            url: "/community_remove",
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

    // community
    $('.newCommunityModalBtn').on('click', function () {
        $('#edtFlag').val("0");
        $('#CommunityModalLabel').text("追加");
    });

    $('#image-upload').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type:'POST',
            url: "/community_store",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function(xhr) {
                var token = $('meta[name="csrf-token"]').attr("content");
                if (token) {
                    return xhr.setRequestHeader("X-CSRF-TOKEN", token);
                }
            },
            cache:false,
            contentType: false,
            processData: false,
            success: function(response) {
                toastr[response['type']](response['result']);
                if(response['type'] == "success"){
                    setTimeout(() => {
                        window.history.go(0);
                    }, 1500);
                }
            },
            error: function(data){
                console.log(data);
            }
        });
    });

    $('.updateCommunity').on('click', function (event) {
        $('#edtFlag').val("1");
        $('#CommunityModalLabel').text('編集');
        $('#edtCommunityId').val($(this).data('id'));
        $('#edtCommunityName').val($(this).data('name'));
        $('#edtCommunityCategory').val($(this).data('category'));
        $('#edtCommunityCategory option[value=' + $(this).data('category') + ']').attr('selected', 'selected');
        var baseElement = $('#uploaded_view');
        var newElement = $('<img>').attr('src', $(this).data('image'));
        baseElement.children('img').remove();
        baseElement.append(newElement);
        baseElement.css('opacity', '1');
        $('#addCommunityModal').modal('toggle');
    });

    $('.removeCommunity').on('click', function (event) {
        $('#CommunityID').val($(this).data('id'));
        $('#removeConfirmModal').modal('toggle');
    });

    $('#removeCommunityConfirmBtn').on('click', function (event) {
        var CommunityID = $('#CommunityID').val();
        $.ajax({
            url: "/community_remove",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: CommunityID },
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

    // matching data
    $('.viewMatchingData').on('click', function (event) {
        var MatchingDataID = $(this).data('id');
        $.ajax({
            url: "/matching_show",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: MatchingDataID },
            beforeSend: function(xhr) {
                var token = $('meta[name="csrf-token"]').attr("content");
                if (token) {
                    return xhr.setRequestHeader("X-CSRF-TOKEN", token);
                }
            },
            success: function(response) {
                var result = response.data[0];
                $('#proposedUserId').val(result['proposed_user_id']);
                $('#proposedUserNickname').val(result['proposed_user_nickname']);
                $('#proposedDate').val(result['proposed_date']);
                $('#acceptedUserId').val(result['accepted_user_id']);
                $('#acceptedUserNickname').val(result['accepted_user_nickname']);
                $('#acceptedDate').val(result['accepted_date']);
                $('#receivingMsgState').val(result['receiving_message_state']);
                $('#proposalState').val(result['proposal_state']);
                $('#MatchingDataModal').modal('toggle');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr['error'](errorThrown);
            }
        });
    });

    $('.removeMatchingData').on('click', function (event) {
        $('#MatchingDataID').val($(this).data('id'));
        $('#removeConfirmModal').modal('toggle');
    });

    $('#removeMatchingDataConfirmBtn').on('click', function (event) {
        var MatchingDataID = $('#MatchingDataID').val();
        $.ajax({
            url: "/matching_remove",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: MatchingDataID },
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

    // violation report.
    $('.viewViolationData').on('click', function (event) {
        var ViolationID = $(this).data('id');
        $.ajax({
            url: "/violation_show",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: ViolationID },
            beforeSend: function(xhr) {
                var token = $('meta[name="csrf-token"]').attr("content");
                if (token) {
                    return xhr.setRequestHeader("X-CSRF-TOKEN", token);
                }
            },
            success: function(response) {
                var result = response.data[0];
                $('#violationID').val(result['violation_id']);
                $('#userID').val(result['user_id']);
                $('#userNickname').val(result['user_nickname']);
                $('#violationDate').val(result['violation_date']);
                $('#violationContent').val(result['violation_content']);
                $('#ViolationModal').modal('toggle');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr['error'](errorThrown);
            }
        });
    });

    $('.removeViolationData').on('click', function (event) {
        $('#ViolationID').val($(this).data('id'));
        $('#removeConfirmModal').modal('toggle');
    });

    $('#removeViolationConfirmBtn').on('click', function (event) {
        var ViolationID = $('#ViolationID').val();
        $.ajax({
            url: "/violation_remove",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: ViolationID },
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

    // community category
    $('.newCategoryModalBtn').on('click', function () {
        $('#edtFlag').val("0");
        $('#CategoryModalLabel').text("追加");
    });

    $('#categoryForm').submit(function(event) {
        event.preventDefault();

        var formData = new FormData(this);
        var categoryName = $('#edtCategoryName').val();

        if (categoryName == '') {
            toastr['warning']("カテゴリ名を入力してください");
        } else {
            if ($('#edtFlag').val() == "0") {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/category_store",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastr[response['type']](response['result']);
                        if (response['type'] == "success") {
                            setTimeout(() => {
                                window.history.go(0);
                            }, 1500);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr['error'](errorThrown);
                    }
                });
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/category_update",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastr[response['type']](response['result']);
                        if (response['type'] == "success") {
                            setTimeout(() => {
                                window.history.go(0);
                            }, 1500);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr['error'](errorThrown);
                    }
                });
            }
        }
    });

    $('.updateCategory').on('click', function (event) {
        $('#edtFlag').val("1");
        $('#CategoryModalLabel').text('編集');
        $('#edtCategoryId').val($(this).data('id'));
        $('#edtCategoryName').val($(this).data('val'));
        $('#addCategoryModal').modal('toggle');
    });

    $('.removeCategory').on('click', function (event) {
        $('#categoryID').val($(this).data('id'));
        $('#removeConfirmModal').modal('toggle');
    });

    $('#removeCategoryConfirmBtn').on('click', function (event) {
        var categoryId = $('#categoryID').val();
        $.ajax({
            url: "/category_remove",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: categoryId },
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

    // identify
    $('.viewIndentifyUser').on('click', function (event) {
        $('#edtFlag').val("1");
        $('#CustomerModalLabel').text('編集');
        var CustomerID = $(this).data('id');
        $.ajax({
            url: "/identify_show",
            type: "POST",
            data: { id: CustomerID },
            beforeSend: function(xhr) {
                var token = $('meta[name="csrf-token"]').attr("content");
                if (token) {
                    return xhr.setRequestHeader("X-CSRF-TOKEN", token);
                }
            },
            success: function(response) {
                var result = response.data[0];
                $('#edtRequestDate').val(result['request_date']);
                $('#edtIdentifyPhoto').val(asset_url + "uploads/" + result['identity_photo']);
                $('#edtIdentifyState').val(result['identity_type'] == '' ? '要求している' : result['identity_type']);
                let bday = new Date(result['birthday']);
                let now = new Date();
                $('#editAge').val(now.getFullYear() - bday.getFullYear());
                $('#edtBirthday').val(result['birthday']);
                $('#edtUserName').val(result['user_name']);
                $('#edtEmail').val(result['email']);
                $('#edtNickName').val(result['user_nickname']);
                $('#edtNickName1').val(result['user_nickname']);
                $('#edtAddress').val(result['residence']);
                $('#edtCommunity').val(result['community']);
                $('#edtHeight').val(result['height']);
                $('#edtBodyType').val(result['body_type']);
                $('#edtUsePurpose').val(result['use_purpose']);
                $('#edtIntroBadge').val(result['intro_badge']);
                $('#edtIntroduce').val(result['introduce']);
                $('#edtPlanType').val(result['plan_type']);
                $('#edtLikesRate').val(result['likes_rate']);
                $('#edtCoin').val(result['coin']);
                $('#edtIdentityState').val(result['identity_state']);
                $('#edtUserPhoto').attr('src', asset_url + "uploads/" + result['photo1']);
                $('#edtIdentifyPhoto').attr('src', asset_url + "uploads/" + result['identity_photo']);
                $('.additional').removeClass('d-none');
                $('#addCustomerModal').modal('toggle');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr['error'](errorThrown);
            }
        });
    });

    $('.activeIndentify').on('click', function () {
        var CustomerID = $(this).data('id');
        var type = $(this).data('type');
        $.ajax({
            url: "/identify_update",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: CustomerID, type: type },
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
                } else if (response['type'] == "error") {
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

    // message
    $('.sent-user').on('click', function () {
        $('#received_by').val($(this).data('val'));
        if($(this).data('val') == 3) {
            $('.user-panel').removeClass('d-none');
        } else {
            if(!$('.user-panel').hasClass('d-none'))
                $('.user-panel').addClass("d-none");
        }
    });

    $('.sendMessageModalBtn').on('click', function () {
        $('#edtFlag').val("0");
        $('#SendMessageModalLabel').text("追加");
        $('#received_id').val('0');
        $('#title').val('');
        $('#content').val('');
        $('#messageID').val('');
        $('#received_by').val('0');
        $('.all-user').prop('checked', true);
        $('.user-panel').addClass('d-none');
    });

    $('#sendMessageBtn').on('click', function () {

        if($('#received_by').val() == '') {
            toastr['warning']('メッセージを受信するユーザーを選択してください');
        } else if($('.msg-title').val() == '') {
            toastr['warning']('タイトルを入力してください');
        } else if($('.msg-content').val() == '') {
            toastr['warning']('内容を入力してください');
        } else {
            var received_id = $('#received_by').val() == '3' ? $('#received_user').val() : "0";

            if($('#edtFlag').val() == "0"){
                $.ajax({
                    url: "/message_store",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { title: $('.msg-title').val(),
                            content: $('.msg-content').val(),
                            received_id: received_id,
                            received_by : $('#received_by').val()
                        },
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
            }
            else {
                $.ajax({
                    url: "/message_update",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { title: $('.msg-title').val(),
                            content: $('.msg-content').val(),
                            received_id: received_id,
                            received_by : $('#received_by').val(),
                            id: $('#messageID').val()
                        },
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
            }
        }
    });

    $('.updateMessage').on('click', function (event) {
        $('#edtFlag').val("1");
        $('#SendMessageModalLabel').text('編集');
        $('.user-panel').addClass('d-none');
        var MessageID = $(this).data('id');
        $.ajax({
            url: "/message_show",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: MessageID },
            beforeSend: function(xhr) {
                var token = $('meta[name="csrf-token"]').attr("content");
                if (token) {
                    return xhr.setRequestHeader("X-CSRF-TOKEN", token);
                }
            },
            success: function(response) {
                var result = response.data;
                $('#messageID').val(result['id']);
                $('.msg-title').val(result['title']);
                $('#received_by').val(result['received_by']);
                $('.msg-content').val(result['content']);
                if(result['received_by'] == "3") {
                    $('.user-panel').removeClass('d-none');
                    $('#received_user').val(result['received_id']).trigger('change');
                }

                switch(result['received_by']) {
                    case 0:
                        $('.all-user').prop('checked', true);
                        break;
                    case 1:
                        $('.free-user').prop('checked', true);
                        break;
                    case 2:
                        $('.paid-user').prop('checked', true);
                        break;
                    case 3:
                        $('.person').prop('checked', true);
                        break;
                    default:
                        break;
                }

                $('#sendMessageModal').modal('toggle');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr['error'](errorThrown);
            }
        });
    });

    $('.removeMessage').on('click', function (event) {
        $('#MessageID').val($(this).data('id'));
        $('#removeConfirmModal').modal('toggle');
    });

    $('#removeMessageConfirmBtn').on('click', function (event) {
        var MessageID = $('#MessageID').val();
        $.ajax({
            url: "/message_remove",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: MessageID },
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

    function loginBtn()
    {
        var loginId = $('#loginID').val();
        var password = $('#password').val();
        // if(validation(loginId, password) == false)
        // {
        //     return;
        // }
        console.log("Hello world")
        $.ajax({
            url: "/login_action",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { loginId : loginId, password : password},
            beforeSend: function(xhr) {
                var token = $('meta[name="csrf-token"]').attr("content");
                if (token) {
                    return xhr.setRequestHeader("X-CSRF-TOKEN", token);
                }
                console.log(token);
            },
            success: function(response) {
                console.log(response['type']);
                toastr[response['type']](response['result']);
                // alert(response['result']);
                if(response['type'] == "success"){
                    setTimeout(() => {
                        location.href = "/dashboard";
                    }, 1500);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr['error'](errorThrown);
            }
        });
    }


    function validation(Id, Pass)
    {
        if(Id == "")
        {
            toastr['error']("Please enter your ID & Email.");
            return false;
        }
        if (!isValidEmail(Id)) {
            toastr['error']("Your email format is incorrect.");
            return false;
          }
        if(Pass == "")
        {
            toastr['error']("Please enter your Password.");
            return false;
        }
        return true;
    }

    function isValidEmail(email) {
        // Regular expression for email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        return emailRegex.test(email);
    }

    function changeYear(year) {
        // $.ajax({
        //     type: 'POST',
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //       },
        //     url: '/change_year',
        //     data: {
        //         year: year
        //     },
        //     beforeSend: function(xhr) {
        //         var token = $('meta[name="csrf-token"]').attr("content");
        //         if (token) {
        //             return xhr.setRequestHeader("X-CSRF-TOKEN", token);
        //         }
        //         console.log(token);
        //     },
        //     success: function (data) {
        //         // Handle the response from the controller if needed
        //         console.log(data);
        //     }
        // });
        document.forms[1].submit();
    }
