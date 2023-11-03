/**
 * Created by Jon on 10/28/2016.
 */
function Save(){ var par = $(this).parent().parent();
    //var tdProduct = par.children("td:nth-child(1)");
    var tdQuantity = par.children("td:nth-child(2)");
    var tdPrice = par.children("td:nth-child(3)");
    var tdDate = par.children("td:nth-child(4)");
    var tdType = par.children("td:nth-child(5)");
    var tdButtons = par.children("td:nth-child(6)");
    var id = par.data('id');
    var product_id = par.data('product_id');

    //tdProduct.html(tdProduct.children("input[type=text]").val());
    tdQuantity.html(tdQuantity.children("input[type=text]").val());
    tdPrice.html(tdPrice.children("input[type=number]").val());
    tdDate.html(tdDate.children("input[type=text]").val());
    tdType.html(tdType.children("select").val());
    tdButtons.html('<button class="btnEdit btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></button><button class="btnDelete btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></button>');
    $(".btnEdit").bind("click", Edit);
    $(".btnDelete").bind("click", Delete);

    var result = {'id': id, 'product_id': product_id, 'quantity': tdQuantity.html(), 'price': tdPrice.html(), 'date': tdDate.html(), 'type': tdType.html()};

    $.ajax({
        method: "post",
        dataType: 'json',
        data: result,
        url: 'orders/update_order',
        success: function(data) {
        }
    });
};

function Edit(){
    var par = $(this).parent().parent();
    //var tdProduct = par.children("td:nth-child(1)");
    var tdQuantity = par.children("td:nth-child(2)");
    var tdPrice = par.children("td:nth-child(3)");
    var tdDate = par.children("td:nth-child(4)");
    var tdType = par.children("td:nth-child(5)");
    var tdButtons = par.children("td:nth-child(6)");

    //tdProduct.html("<input type='text' style='width: 100%;' id='txtProduct' value='"+tdProduct.html()+"'/>");
    tdQuantity.html("<input type='text' style='width: 100%;' id='txtQuantity' value='"+tdQuantity.html()+"'/>");
    tdPrice.html("<input type='number' style='width: 100%;' step='0.01' id='txtPrice' value='"+tdPrice.html()+"'/>");
    tdDate.html("<input type='text' style='width: 100%;' id='txtDate' value='"+tdDate.html()+"'/>");
    tdType.html("<select style='width: 100%;' id='txtType'><option value='Օրավարձ'>Օրավարձ</option><option value='Վաճառք'>Վաճառք</option>");
    tdButtons.html('<button class="btnSave btn-info btn-xs"><span class="glyphicon glyphicon-ok"></span></button>');
    if(localStorage.getItem('username') == 'admin123457')
    {
        $(".btnSave").bind("click", Save);
        $(".btnEdit").bind("click", Edit);
        $(".btnDelete").bind("click", Delete);
    }

};

function Delete(){
    var par = $(this).parent().parent();
    var id = par.data('id');
    var product_id = par.data('product_id');
    var result = {'id': id, 'product_id': product_id};
    par.remove();
    $.ajax({
        method: "post",
        dataType: 'json',
        data: result,
        url: 'orders/delete_order',
        success: function(data) {
        }
    });
};


function SaveOwn(){ var par = $(this).parent().parent();
    //var tdProduct = par.children("td:nth-child(1)");
    var tdQuantity = par.children("td:nth-child(2)");
    var tdDate = par.children("td:nth-child(3)");
    var tdButtons = par.children("td:nth-child(4)");
    var id = par.data('id');
    var product_id = par.data('product_id');

    //tdProduct.html(tdProduct.children("input[type=text]").val());
    tdQuantity.html(tdQuantity.children("input[type=text]").val());
    tdDate.html(tdDate.children("input[type=text]").val());
    tdButtons.html('<button class="btnEditOwn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></button><button class="btnDeleteOwn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></button>');
    $(".btnEditOwn").bind("click", EditOwn);
    $(".btnDeleteOwn").bind("click", DeleteOwn);

    var result = {'id': id, 'product_id': product_id, 'quantity': tdQuantity.html(), 'date': tdDate.html()};

    $.ajax({
        method: "post",
        dataType: 'json',
        data: result,
        url: 'orders/update_order_own',
        success: function(data) {
        }
    });
};

function EditOwn(){ var par = $(this).parent().parent();
    //var tdProduct = par.children("td:nth-child(1)");
    var tdQuantity = par.children("td:nth-child(2)");
    var tdDate = par.children("td:nth-child(3)");
    var tdButtons = par.children("td:nth-child(4)");

    //tdProduct.html("<input type='text' style='width: 100%;' id='txtProduct' value='"+tdProduct.html()+"'/>");
    tdQuantity.html("<input type='text' style='width: 100%;' id='txtQuantity' value='"+tdQuantity.html()+"'/>");
    tdDate.html("<input type='text' style='width: 100%;' id='txtDate' value='"+tdDate.html()+"'/>");
    tdButtons.html('<button class="btnSaveOwn btn-info btn-xs"><span class="glyphicon glyphicon-ok"></button>');

    if(localStorage.getItem('username') == 'admin123457')
    {
        $(".btnSaveOwn").bind("click", SaveOwn);
        $(".btnEditOwn").bind("click", EditOwn);
        $(".btnDeleteOwn").bind("click", DeleteOwn);
    }

};

function DeleteOwn(){
    var par = $(this).parent().parent();
    var id = par.data('id');
    var product_id = par.data('product_id');
    var result = {'id': id, 'product_id': product_id};
    par.remove();
    $.ajax({
        method: "post",
        dataType: 'json',
        data: result,
        url: 'orders/delete_order',
        success: function(data) {
        }
    });
};



function SaveGB(){ var par = $(this).parent().parent();
    //var tdProduct = par.children("td:nth-child(1)");
    var tdQuantity = par.children("td:nth-child(2)");
    var tdPrice = par.children("td:nth-child(3)");
    //var tdUseless = par.children("td:nth-child(3)");
    var tdDate = par.children("td:nth-child(4)");
    var tdButtons = par.children("td:nth-child(5)");
    var id = par.data('id');
    var product_id = par.data('product_id');

    //tdProduct.html(tdProduct.children("input[type=text]").val());
    tdQuantity.html(tdQuantity.children("input[type=text]").val());
    tdPrice.html(tdPrice.children("input[type=text]").val());
    //tdUseless.html(tdUseless.children("input[type=text]").val());
    tdDate.html(tdDate.children("input[type=text]").val());
    tdButtons.html('<button class="btnEditGB btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></button><button class="btnDeleteGB btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></button>');
    $(".btnEditGB").bind("click", EditGB);
    $(".btnDeleteGB").bind("click", DeleteGB);

    var result = {'id': id, 'product_id': product_id, 'quantity': tdQuantity.html(), 'product_price': tdPrice.html(), 'date': tdDate.html()};

    $.ajax({
        method: "post",
        dataType: 'json',
        data: result,
        url: 'giveback/update_giveback',
        success: function(data) {
        }
    });
};

function EditGB(){ 
    var par = $(this).parent().parent();
    //var tdProduct = par.children("td:nth-child(1)");
    var tdQuantity = par.children("td:nth-child(2)");
    var tdPrice = par.children("td:nth-child(3)");
    //var tdUseless = par.children("td:nth-child(3)");
    var tdDate = par.children("td:nth-child(4)");
    var tdButtons = par.children("td:nth-child(5)");

    //tdProduct.html("<input type='text' style='width: 100%;' id='txtProduct' value='"+tdProduct.html()+"'/>");
    tdQuantity.html("<input type='text' style='width: 100%;' id='txtQuantity' value='"+tdQuantity.html()+"'/>");
    tdPrice.html("<input type='text' style='width: 100%;' id='txtPrice' value='"+tdPrice.html()+"'/>");
    //tdUseless.html("<input type='text' style='width: 100%;' id='txtUseless' value='"+tdUseless.html()+"'/>");
    tdDate.html("<input type='text' style='width: 100%;' id='txtDate' value='"+tdDate.html()+"'/>");
    tdButtons.html('<button class="btnSaveGB btn-info btn-xs"><span class="glyphicon glyphicon-ok"></button>');

    if(localStorage.getItem('username') == 'admin123457')
    {
        $(".btnSaveGB").bind("click", SaveGB);
        $(".btnEditGB").bind("click", EditGB);
        $(".btnDeleteGB").bind("click", DeleteGB);
    }

};

function DeleteGB(){
    var par = $(this).parent().parent();
    var id = par.data('id');
    var product_id = par.data('product_id');
    var result = {'id': id, 'product_id': product_id};
    par.remove();
    $.ajax({
        method: "post",
        dataType: 'json',
        data: result,
        url: 'giveback/delete_giveback',
        success: function(data) {
        }
    });
};


function SaveP(){ var par = $(this).parent().parent();
    //var tdProduct = par.children("td:nth-child(1)");
    var tdAmount = par.children("td:nth-child(1)");
    var tdDate = par.children("td:nth-child(2)");
    var tdButtons = par.children("td:nth-child(3)");
    var id = par.data('id');

    //tdProduct.html(tdProduct.children("input[type=text]").val());
    tdAmount.html(tdAmount.children("input[type=text]").val());
    tdDate.html(tdDate.children("input[type=text]").val());
    tdButtons.html('<button class="btnEditP btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></button><button class="btnDeleteP btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></button>');
    $(".btnEditP").bind("click", EditP);
    $(".btnDeleteP").bind("click", DeleteP);

    var result = {'id': id, 'amount': tdAmount.html(), 'date': tdDate.html()};

    $.ajax({
        method: "post",
        dataType: 'json',
        data: result,
        url: 'payment/update_payment',
        success: function(data) {
        }
    });
};

function EditP(){ var par = $(this).parent().parent();
    //var tdProduct = par.children("td:nth-child(1)");
    var tdAmount = par.children("td:nth-child(1)");
    var tdDate = par.children("td:nth-child(2)");
    var tdButtons = par.children("td:nth-child(3)");

    //tdProduct.html("<input type='text' style='width: 100%;' id='txtProduct' value='"+tdProduct.html()+"'/>");
    tdAmount.html("<input type='text' style='width: 100%;' id='txtQuantity' value='"+tdAmount.html()+"'/>");
    tdDate.html("<input type='text' style='width: 100%;' id='txtDate' value='"+tdDate.html()+"'/>");
    tdButtons.html('<button class="btnSaveP btn-info btn-xs"><span class="glyphicon glyphicon-ok"></button>');

    if(localStorage.getItem('username') == 'admin123457')
    {
        $(".btnSaveP").bind("click", SaveP);
        $(".btnEditP").bind("click", EditP);
        $(".btnDeleteP").bind("click", DeleteP);
    }

};

function DeleteP(){
    var par = $(this).parent().parent();
    var id = par.data('id');
    var result = {'id': id};
    par.remove();
    $.ajax({
        method: "post",
        dataType: 'json',
        data: result,
        url: 'payment/delete_payment',
        success: function(data) {
        }
    });
};



$(document).ready(function(){


    setTimeout(function(){ 
        $('#products_table #first-th').css('width', 'auto');
        if(localStorage.getItem('username') == null && window.location.href.substr(window.location.href.lastIndexOf('/') + 1) != '' && window.location.href.substr(window.location.href.lastIndexOf('/') + 1) != 'welcome')
        {
            window.location.href = window.location.origin;
        }
    }, 1000);

    $('#products_table table').DataTable({
		'pageLength': 17, 
		dom: "Bflrtip",   
          buttons: [
            {extend: "copy", className: "btn-sm"},
            {extend: "csv", className: "btn-sm"},
            {extend: "excel", className: "btn-sm"},
            {extend: "print", className: "btn-sm"},
          ],
	aLengthMenu: [
        [25, 50, 100, 200, -1],
        [25, 50, 100, 200, "All"]
    ]});
    $.fn.datepicker.defaults.format = "dd/mm/yyyy";
    $('.datepicker').datepicker({
        "todayHighlight": true,
        "autoclose": true
    });
    $('.datepicker').datepicker("setDate", new Date());
    //dashboard
    $(document).on('click', '.daily_order', function(){
        var el = $(this);
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'dashboard/get_product_client_table/'+el.data('id'),
            success: function(data) {
                $('#product_client_popup').html(data.data);
                $('#product_client_popup').show();
            }
        });
    });
/*
    $(document).on('click', '#logoutBtn', function(event){
        localStorage.clear();
        window.location.href = window.location.origin;
    });
*/
    $(document).on('click', '.goToMenu', function(event){
        window.location.href = window.location.origin+"/welcome/login/"+localStorage.getItem('username');
    });

    $(document).on('change', '#debt_date', function(){
        var date = $(this).val();
        var client_id = $(this).data('client_id');
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'dashboard/get_client_info/?client_id='+client_id+'&date='+date,
            success: function(data) {
                $('#client_debt').text('Պարտք: '+data+' դրամ');
                $('.datepicker-dropdown').hide();
            }
        });
    })

/*
    $(document).on('change', '#debts_date', function(){
        var date = $(this).val();
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'debts/get_debts?date='+date, // 'dashboard/get_debts/?date='+date,
            success: function(data) {
                $('#depts_table').html(data.html);
                $('.datepicker-dropdown').hide();
            }
        });
    })
*/	
    $(document).on('click', '#debts_table', function(){
        var el = $(this);
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'dashboard/get_debts',
            success: function(data) {
                $('#myModal .modal-content').html(data.html);
            }
        });
    })
	

    $(document).on('click', '.get_product', function(){
        var el = $(this);
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'getproduct/get_products/?product_name='+el.data('product_name'),
            success: function(data) {
                $('#myModal .modal-content').html(data.html);
                $('.modal-title').text(el.data('product_name'));
                $('.modal-body table').DataTable();
            }
        });
    })

    $(document).on('click', '.product_client_info', function(){
        var el = $(this);
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'dashboard/get_product_client_info/?client_id='+el.data('client_id')+'&product_id='+el.data('product_id'),
            success: function(data) {
                $('#myModal .modal-content').html(data.html);
            }
        });
    })

    $(document).on('click', '#set_interval', function(){
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        var start = new Date(date_start);
        var end = new Date(date_end);
        if(start>end)
        {
            alert('Սխալ ինտերվալ');
            return false;
        }
        var client_id = $(this).data('client_id');
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'dashboard/get_client_info/?client_id='+client_id+'&date_start='+date_start+'&date_end='+date_end,
            success: function(data) {
                $('#myModal .modal-content').html(data.html);
                if(localStorage.getItem('username') == 'admin123457')
                {
                    $(".btnEdit").bind("click", Edit);
                    $(".btnDelete").bind("click", Delete);
                    $(".btnEditOwn").bind("click", EditOwn);
                    $(".btnDeleteOwn").bind("click", DeleteOwn);
                    $(".btnEditGB").bind("click", EditGB);
                    $(".btnDeleteGB").bind("click", DeleteGB);
                    $(".btnEditP").bind("click", EditP);
                    $(".btnDeleteP").bind("click", DeleteP);
                }


            }
        });
    })

    $(document).on('click', '#set_interval_product_client', function(){
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        var start = new Date(date_start);
        var end = new Date(date_end);
        if(start>end)
        {
            alert('Սխալ ինտերվալ');
            return false;
        }
        var client_id = $(this).data('client_id');
        var product_id = $(this).data('product_id');
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'dashboard/get_product_client_info/?client_id='+client_id+'&product_id='+product_id+'&date_start='+date_start+'&date_end='+date_end,
            success: function(data) {
                $('#myModal .modal-content').html(data.html);
                if(localStorage.getItem('username') == 'admin123457')
                {
                    $(".btnEdit").bind("click", Edit);
                    $(".btnDelete").bind("click", Delete);
                    $(".btnEditOwn").bind("click", EditOwn);
                    $(".btnDeleteOwn").bind("click", DeleteOwn);
                    $(".btnEditGB").bind("click", EditGB);
                    $(".btnDeleteGB").bind("click", DeleteGB);
                    $(".btnEditP").bind("click", EditP);
                    $(".btnDeleteP").bind("click", DeleteP);
                }

            }
        });
    })

    $(document).on('click', '#set_interval_product', function(){
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        var start = new Date(date_start);
        var end = new Date(date_end);
        if(start>end)
        {
            alert('Սխալ ինտերվալ');
            return false;
        }
        var product_id = $(this).data('product_id');
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'dashboard/get_product_info/?product_id='+product_id+'&date_start='+date_start+'&date_end='+date_end,
            success: function(data) {
                $('#myModal .modal-content').html(data.html);
                if(localStorage.getItem('username') == 'admin123457')
                {
                    $(".btnEdit").bind("click", Edit);
                    $(".btnDelete").bind("click", Delete);
                    $(".btnEditOwn").bind("click", EditOwn);
                    $(".btnDeleteOwn").bind("click", DeleteOwn);
                    $(".btnEditGB").bind("click", EditGB);
                    $(".btnDeleteGB").bind("click", DeleteGB);
                    $(".btnEditP").bind("click", EditP);
                    $(".btnDeleteP").bind("click", DeleteP);
                }

            }
        });
    })

    $(document).on('click', '.client_info', function(){
        var el = $(this);
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'dashboard/get_client_info/?client_id='+el.data('client_id'),
            success: function(data) {
                $('#myModal .modal-content').html(data.html);
                if(localStorage.getItem('username') == 'admin123457')
                {
                    $(".btnEdit").bind("click", Edit);
                    $(".btnDelete").bind("click", Delete);
                    $(".btnEditOwn").bind("click", EditOwn);
                    $(".btnDeleteOwn").bind("click", DeleteOwn);
                    $(".btnEditGB").bind("click", EditGB);
                    $(".btnDeleteGB").bind("click", DeleteGB);
                    $(".btnEditP").bind("click", EditP);
                    $(".btnDeleteP").bind("click", DeleteP);
                }

            }
        });
    })

    $(document).on('click', '.product_info', function(){
        var el = $(this);
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'dashboard/get_product_info/?product_id='+el.data('product_id'),
            success: function(data) {
                $('#myModal .modal-content').html(data.html);
            }
        });
    })

    //giveback
    $(document).on('click', '#new_giveback', function(){
        $('#new_giveback_popup').show();
    });
    $(document).on('click', '#close_new_giveback', function(event){
        event.preventDefault();
        $('#new_giveback_popup').hide();
    });
    $(document).on('change', '#client_gives', function(){
        var client_id = $(this).val();
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'giveback/get_client_products/'+client_id,
            success: function(data) {
                $('#product_given').html(data.result);
				$('#products').html(data.products);
				$(".selectpicker").selectpicker('refresh');
				
            }
        });
    })

    $(document).on('change', '#product_given', function(){
        var quantity = $('#product_given option:selected').data('q');
        $('#product_q').val(quantity);
        $("#product_q").data('q',quantity);
    })
    $(document).on('change', '#product_q', function(){
        var quantity = $(this).data('q');
        if($(this).val() > quantity)
        {
            $('#product_q').val(quantity);
        }
    })

    //payment
    $(document).on('click', '#new_payment', function(){
        $('#new_payment_popup').show();
    });
    $(document).on('click', '#close_new_payment', function(event){
        event.preventDefault();
        $('#new_payment_popup').hide();
    });


    //products
    $(document).on('click', '#new_product', function(){
        $('#new_product_popup').show();
        $('#edit_product_popup').hide();
    });
    $(document).on('click', '#close_new_product', function(event){
        event.preventDefault();
        $('#new_product_popup').hide();
    });
    $(document).on('click', '#edit_product', function(){
        $('#edit_product_popup').show();
        $('#new_product_popup').hide();
    });
    $(document).on('click', '#close_edit_product', function(event){
        event.preventDefault();
        $('#edit_product_popup').hide();
    });
    $(document).on('change', '#product_to_edit', function(){
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'products/get_product_details/'+$('#product_to_edit').val(),
            success: function(data) {
                var product = data.data[0];
                $('#edit_product_name').val(product.name);
                $('#edit_type').val(product.type);
                $('#edit_quantity').val(product.quantity);
                $('#edit_new_quantity').val(product.new_quantity);
                $('#edit_bad_quantity').val(product.bad_quantity);
                $('#edit_useless_quantity').val(product.useless_quantity);
                $('#edit_product_form').attr('action', 'products/edit_product/'+product.id);
            }
        });
    })

    //clients
    $(document).on('click', '#new_client', function(){
        $('#new_client_popup').show();
        $('#edit_client_popup').hide();
    });
    $(document).on('click', '#close_new_client', function(event){
        event.preventDefault();
        $('#new_client_popup').hide();
    });
    $(document).on('click', '#edit_client', function(){
        $('#edit_client_popup').show();
        $('#new_client_popup').hide();
    });
    $(document).on('click', '#close_edit_client', function(event){
        event.preventDefault();
        $('#edit_client_popup').hide();
    });
    $(document).on('change', '#client_to_edit', function(){
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'clients/get_client_details/'+$('#client_to_edit').val(),
            success: function(data) {
                var client = data.data[0];
                $('#edit_client_name').val(client.name);
                if(client.own == 'yes')
                {
                    $('#edit_own').prop('checked', true);
                }
                else
                {
                    $('#edit_own').prop('checked', false);
                }
                $('#edit_debt').val(client.debt);
                $('#edit_client_form').attr('action', 'clients/edit_client/'+client.id);
            }
        });
    })

    //orders
    var formNumber = 1;

    $(document).on('click', '#new_order', function(){
        $('#new_order_popup').show();
    });
    $(document).on('click', '#close_new_order', function(event){
        event.preventDefault();
        $('#new_order_popup').hide();
    });
    $(document).on('change', '.new_client_for_order', function(){
        var element = $(this).parent();
        element.find('.new_client_name').toggle();
        if(element.is(":checked"))
        {
            element.find('.client_to_pick').prop('disabled', 'disabled');
            element.find('.product_price').show();
        }
        else
        {
            element.find('.client_to_pick').prop('disabled', false);
            if(element.find('.product_price').attr('data-show') == 'false')
            {
                element.find('.product_price').hide();
            }
        }
    })
    $(document).on('change', '.own_client', function(){
        var element = $(this).parent().parent();
        if($(this).is(":checked"))
        {
            element.find('.product_price').hide();
        }
        else
        {
            element.find('.product_price').show();
        }
    })
    $(document).on('change', '.sale', function(){
        var element = $(this).parent();
        if($(this).is(":checked"))
        {
            element.find('.daily').prop('checked', false);
        }
        else
        {
            element.find('.daily').prop('checked', true);
        }
    })
    $(document).on('change', '.daily', function(){
        var element = $(this).parent();
        if($(this).is(":checked"))
        {
            element.find('.sale').prop('checked', false);
        }
        else
        {
            element.find('.sale').prop('checked', true);
        }
    })
    $(document).on('change', '.product_to_pick', function(e){
		e.stopImmediatePropagation();
        var element = $(this).parent();
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'products/get_product_details/'+$(this).val(),
            success: function(data) {
                var product = data.data[0];
                element.find('.product_type').text(product.type);
                element.find('.product_quantity').attr('max' ,(product.quantity+product.new_quantity-product.daily_order));
                if(parseInt(element.find('.product_quantity').val()) > product.quantity)
                {
                    element.find('.product_quantity').val(product.quantity);
                }
            }
        });
    })
    $(document).on('change', '.client_to_pick', function(e){
		e.stopImmediatePropagation();
        var element = $(this); //.parent(); //.attr('id');//
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'clients/get_client_details/'+$(this).val(),
            success: function(data) {
                var client = data.data[0];
				var ee = element.attr('id');
				var el = ee.replace("client_to_pick", "product_price"); // client_to_pick1
				//alert(element.attr('id'));
                if(client.own == 'yes')
                {
					$('#'+el).hide();
                    //element.find('.product_price').hide();
                    //element.find('.product_price').attr('data-show', 'false');
                }
                else
                {
					$('#'+el).show();
                    //element.find('.product_price').show();
                    //element.find('.product_price').attr('data-show', 'true');
                }
				//$('.selectpicker').selectpicker('refresh');
            }
        });
    })
    $(document).on('change', '.product_quantity', function(){
        var element = $(this);
        var max = element.attr('max');
        if(parseInt(element.val()) > max)
        {
            element.val(max);
        }
    })

    $(document).on('click', '#add_form', function(event) {
		$('select').selectpicker('destroy');
        event.preventDefault();
		
        formNumber++;
        $('#post_number').val(formNumber);
        var num = $('#new_order_form .clonedSection').length;
        var newNum  = num + 1;
		//$('.selectpicker').selectpicker('destroy');
		
        var newSection = $('#clonedSection' + num).clone().attr('id', 'clonedSection' + newNum);

        newSection.find('select[name*="client_to_pick"]').attr('id', 'client_to_pick' + newNum).attr('name', 'client_to_pick' + newNum);
        newSection.find('input[name*="new_client"]').attr('id', 'new_client_for_order' + newNum).attr('name', 'new_client' + newNum);
        newSection.find('input[name*="own_client"]').attr('id', 'own_client' + newNum).attr('name', 'own_client' + newNum);
        newSection.find('input[name*="new_client_name"]').attr('name', 'new_client_name' + newNum);
        newSection.find('select[name*="product_to_pick"]').attr('id', 'product_to_pick' + newNum).attr('name', 'product_to_pick' + newNum);
        newSection.find('input[name*="product_quantity"]').attr('id', 'product_quantity' + newNum).attr('name', 'product_quantity' + newNum);
        newSection.find('input[name*="daily"]').attr('id', 'daily' + newNum).attr('name', 'daily' + newNum);
        newSection.find('input[name*="sale"]').attr('id', 'sale' + newNum).attr('name', 'sale' + newNum);
        newSection.find('input[name*="product_price"]').attr('name', 'product_price' + newNum);
        newSection.find('input[name*="date"]').attr('name', 'date' + newNum);
        newSection.find('.new_client_name').attr('id', 'new_client_name' + newNum);
        newSection.find('.product_type').attr('id', 'product_type' + newNum);
        newSection.find('.product_price').attr('id', 'product_price' + newNum);
		
		//$('.selectpicker').selectpicker('destroy');
		//newSection.find('select').removeClass('selectpicker');
		//newSection.find('select').selectpicker('destroy', true);
        $('.clonedSection').last().after(newSection);
		//$('.selectpicker').selectpicker('refresh');
		//$('select').removeClass("selectpicker").addClass("selectpicker");
		
		
		
		$('select').selectpicker();
		
		//newSection.find('.client_to_pick').removeClass('selectpicker');
		
		//$('#client_to_pick'+newNum).selectpicker();
		
		//newSection.find('.client_to_pick').selectpicker('refresh');
		
		

        $('#del_form').removeAttr("disabled");
		
    });

    $(document).on('click', '#del_form', function(event) {
        event.preventDefault();
        formNumber--;
        $('#post_number').val(formNumber);
        var num = $('.clonedSection').length; // how many "duplicatable" input fields we currently have
        $('#clonedSection' + num).remove();     // remove the last element

        // if only one element remains, disable the "remove" button
        if (num-1 == 1)
            $('#del_form').attr('disabled','disabled');
    });

    $('#del_form').attr('disabled','disabled');

})
