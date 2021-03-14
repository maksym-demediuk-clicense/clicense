$(document).ready(function(){
  $("#login-form").submit(function() {
    var username = $('#login').val(); 
    var password = $('#password').val();	
    if (username && password) { 
		$.ajax({
        type: "GET",
        url: "/ajax/login.php", 
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: "username=" + username + "&password=" + password,
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
          $('div#log_result').text("<br>" + "responseText: " + XMLHttpRequest.responseText 
            + ", textStatus: " + textStatus 
            + ", errorThrown: " + errorThrown);       
        }, 
        success: function(data){
          if (data.error) { 
		    $('#log_result').append("<br>");
            $('#log_result').text(data.error);
			$('#log_result').addClass("alert alert-danger");
          } // 
          else { 
		    $('#log_result').append("<br>");
            $('#log_result').text(data.success);
			$('#log_result').removeClass("alert alert-danger");			
			$('#log_result').addClass("alert alert-success");
			setTimeout(function() {
			document.location.href = '/panel';
			}, 2000);     
          } 
        } 
      });
    } 
    else {
	  $('#log_result').append("<br>");
      $('#log_result').text("Enter login and password");
      $('#log_result').addClass("alert alert-danger");
    } 
    $('#log_result').fadeIn();
    return false;
  });
  $("#setting-form").submit(function () {
      var old_pass = $("#old_pass").val();
      var new_pass = $("#new_pass").val();
      var rep_pass = $("#rep_pass").val();
      $.ajax({
         type: "GET",
          url: "/ajax/setting.php",
          contentType: "application/json; charset=utf-8",
          dataType: "json",
          data: "old_pass=" + old_pass + "&new_pass=" + new_pass + "&rep_pass=" + rep_pass,
          error: function(XMLHttpRequest, textStatus, errorThrown) {
              $('div#set_result').text("responseText: " + XMLHttpRequest.responseText
                  + ", textStatus: " + textStatus
                  + ", errorThrown: " + errorThrown);
                  console.log(XMLHttpRequest.status);
          },
          success: function(data){
              if (data.error) {
                  $('#set_result').append("<br>");
                  $('#set_result').text(data.error);
                  $('#set_result').addClass("alert alert-danger");
              } //
              else {
                  $('#set_result').append("<br>");
                  $('#set_result').text(data.success);
                  $('#set_result').removeClass("alert alert-danger");
                  $('#set_result').addClass("alert alert-success");
                  $('#old_pass').val('');
                  $('#new_pass').val('');
                  $('#rep_pass').val('');
              }
          }
      });
      $('#set_result').fadeIn();
      return false;
  });
  $("#product-form").submit(function () {
        var name = $("#name_product").val();
        var ver = $("#ver_product").val();
		var contract = $("#contract_product").val();
		var api = $("#api_product").val();
		var account = $("#account_product").val();
        $.ajax({
            type: "GET",
            url: "/ajax/setting.php",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: "name=" + name + "&version=" + ver + "&contract=" + contract + "&api=" + api + "&account=" + account,
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#prod_result').append("<br>");
                    $('#prod_result').text("<br>" + "responseText: " + XMLHttpRequest.responseText
                        + ", textStatus: " + textStatus
                        + ", errorThrown: " + errorThrown);
                    $('#prod_result').addClass("alert alert-danger");
            },
            success: function(data){
                if (data.error) {
                    $('#prod_result').append("<br>");
                    $('#prod_result').text(data.error);
                    $('#prod_result').addClass("alert alert-danger");
                } //
                else {
                    $('#prod_result').append("<br>");
                    $('#prod_result').text(data.success);
                    $('#prod_result').removeClass("alert alert-danger");
                    $('#prod_result').addClass("alert alert-success");
                    setTimeout(function() {
                        document.location.href = '/panel/products';
                        $('#prod_result').removeClass("alert alert-success");
                        $('#prod_result').val('');
                        $('#prod_result').text('');
                    }, 2000);
                }
            }
        });
        $('#prod_result').fadeIn();
    return false;
    });
    $("#license-form").submit(function () {
        var email = $("#email").val();
        var prod = $("#plugin_id").val();
        console.log(prod);
        $.ajax({
            type: "GET",
            url: "/ajax/new_license.php",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: "product_id=" + prod + "&email=" + btoa(email),
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#prod_result').append("<br>");
                    $('#prod_result').text("<br>" + "responseText: " + XMLHttpRequest.responseText
                        + ", textStatus: " + textStatus
                        + ", errorThrown: " + errorThrown);
                    $('#prod_result').addClass("alert alert-danger");
            },
            success: function(data){
                if (data.error) {
                    $('#prod_result').append("<br>");
                    $('#prod_result').text(data.error);
                    $('#prod_result').addClass("alert alert-danger");
                } //
                else {
                    $('#prod_result').append("<br>");
                    $('#prod_result').text(data.success);
                    $('#prod_result').removeClass("alert alert-danger");
                    $('#prod_result').addClass("alert alert-success");
                    setTimeout(function() {
                        document.location.href = '/panel/products/' + prod + '/licenses';
                        $('#prod_result').removeClass("alert alert-success");
                        $('#prod_result').val('');
                        $('#prod_result').text('');
                    }, 2000);
                }
            }
        });
        $('#prod_result').fadeIn();
    return false;
    });
  $("#change-form").submit(function () {
        var cid = $("#prod_id").val();
        var cname = $("#cname_product").val();
        var cver = $("#cver_product").val();
        $.ajax({
            type: "GET",
            url: "/ajax/setting.php",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: "cid=" + cid + "&cname=" + cname + "&cver=" + cver,
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $('#change_result').append("<br>");
                $('#change_result').text("<br>" + "responseText: " + XMLHttpRequest.responseText
                    + ", textStatus: " + textStatus
                    + ", errorThrown: " + errorThrown);
                $('#change_result').addClass("alert alert-danger");
            },
            success: function(data){
                if (data.error) {
                    $('#change_result').append("<br>");
                    $('#change_result').text(data.error);
                    $('#change_result').addClass("alert alert-danger");
                } //
                else {
                    $('#change_result').append("<br>");
                    $('#change_result').text(data.success);
                    $('#change_result').removeClass("alert alert-danger");
                    $('#change_result').addClass("alert alert-success");
                    setTimeout(function() {
                        document.location.href = '/panel/products';
                        $('#change_result').removeClass("alert alert-success");
                        $('#change_result').val('');
                        $('#change_result').text('');
                    }, 2000);
                }
            }
        })
        $('#change_result').fadeIn();
        return false;
    });
  $("#delete-form").submit(function () {
        var cid = $("#delid").val();
        $.ajax({
            type: "GET",
            url: "/ajax/setting.php",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: "delid=" + cid,
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $('#delete_result').append("<br>");
                $('#delete_result').text("<br>" + "responseText: " + XMLHttpRequest.responseText
                    + ", textStatus: " + textStatus
                    + ", errorThrown: " + errorThrown);
                $('#delete_result').addClass("alert alert-danger");
            },
            success: function(data){
                if (data.error) {
                    $('#delete_result').append("<br>");
                    $('#delete_result').text(data.error);
                    $('#delete_result').addClass("alert alert-danger");
                } //
                else {
                    $('#delete_result').append("<br>");
                    $('#delete_result').text(data.success);
                    $('#delete_result').removeClass("alert alert-danger");
                    $('#delete_result').addClass("alert alert-success");
                    setTimeout(function() {
                        document.location.href = '/panel/products';
                        $('#delete_result').removeClass("alert alert-success");
                        $('#delete_result').val('');
                        $('#delete_result').text('');
                    }, 2000);
                }
            }
        });
        $('#delete_result').fadeIn();
        return false;
    });

  $('.sync-product').on( 'click', function () {
    var product_id = $(".sync-product").attr('data-value');
    SyncProduct(product_id);
} );

});



function DeleteLicense(btn, key, prod)
{
    $.ajax({
        type: "GET",
        url: "/ajax/delete_license.php",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: "public_key=" + key + "&product_id=" + prod,
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            console.log("responseText: " + XMLHttpRequest.responseText
                        + ", textStatus: " + textStatus
                        + ", errorThrown: " + errorThrown);
        },
        success: function(data){
            if (data.error) {
                console.log(data.error);
            }
            else {
                var row = btn.parentNode.parentNode;
                row.parentNode.removeChild(row);
                document.location.href = '/panel/products/' + prod + '/licenses';
            }
        }
    });
}
function SyncProduct(prod)
{
    $.ajax({
        type: "GET",
        url: "/ajax/sync_product.php",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: "product_id=" + prod,
        error: function(XMLHttpRequest, textStatus, errorThrown) {

        },
        success: function(data){
            location.reload(true);
        }
    });
}

function DeactiveKey(key_id, text_id) {
    $.ajax({
        type: "GET",
        url: "/ajax/setting.php",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: "keyaid=" + key_id,
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        },
        success: function(data){
            if (data.error) {

                $('#keyaid_' + key_id).removeClass("btn-success");
                $('#keyaid_' + key_id).addClass("btn-danger");
                $('#keyaid_' + key_id).text('Deactive');
                if(text_id > -1){
                    $('#key_active_' + text_id).text(data.error);
                }
            }
            else {
                $('#keyaid_' + key_id).removeClass("btn-danger");
                $('#keyaid_' + key_id).addClass("btn-success");
                $('#keyaid_' + key_id).text('Active');
                if(text_id > -1){
                    $('#key_active_' + text_id).text(data.success);
                }
            }
        }
    });

}


