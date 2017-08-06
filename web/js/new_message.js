$('#appbundle_message_destructWay').on('change', function(){
    var label = $('label[for=appbundle_message_destructOption]');
    switch($(this).val()) {
        case 'read':
            label.text('Number of link visits');
            break;
        case 'time':
            label.text('Hour after message will be destroyed');
            break;
        default:
            label.text('Desctruct option');
    }
});
var $messageForm = $(document.forms['appbundle_message']);
$messageForm.submit(function(){
    var data = {};

    data['appbundle_message[password]'] = hex_md5($('#appbundle_message_password').val());
    data['appbundle_message[text]'] = encodeMessage($('#appbundle_message_text').val(),data['appbundle_message[password]']);

    var values = $messageForm.serializeArray();
    $.each(values, function(index, value){
        if (data[value.name]) {
            value.value = data[value.name];
        }
    });

    $.ajax({
        data: values,
        method: 'POST',
        dataType: "json"
    }).done(function (responce) {
        console.log(responce);
        $('#new-message-form').hide();
        $('#access-url').attr('href', responce.message_url).text(responce.message_url);
        $('#result').show();
    }).fail(function (responce) {
        console.log(responce);
        alert(responce);
    });

    $messageForm[0].reset();
    return false;
});