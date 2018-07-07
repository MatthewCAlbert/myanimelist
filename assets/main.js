

function toggleNav(){
    if( $("#menu-button").hasClass('is-active') ){
        $("#menu-button").removeClass('is-active');
        $("aside.sidebar").css('width','0');
        $("body").css('overflow','auto');
    }else{
        $("#menu-button").addClass('is-active');
        $("aside.sidebar").css('width','80%');
        $("body").css('overflow','hidden');
    }
}

var tags=[];
function changeTag(x){
    var final_tag = '';
    if( !tags.includes(x) ){ //add
        tags.push(x);
    }else if( tags.includes(x) ){ //remove
        let index = tags.indexOf(x);
        if (index > -1) {
          tags.splice(index, 1);
        }
    }

    tags.forEach(function(element){
        if(element != null){
            final_tag += element+',';
        }
    });
    setCookie('anime-tags',final_tag,30);
    $('input[name=tag]').val(final_tag);
}

var episode_count = 1;
var episodes = [1];

function changeEpisodeCount(x){
    if( episode_count < x ){
        for( let i = 1; i<= x ; i++ ){
            if( !episodes.includes(i) ){
                $('#episode-text').append('<textarea class="form-control hide" name="epsdesc-'+i+'" rows="4" cols="50"></textarea>');
                $('#episode-tag').append('<input type="text" class="form-control hide" name="epstag-'+i+'" />');
                $('#episode-link').append('<input type="text" class="form-control hide" name="epslink-'+i+'" />');
                $('#episode-selector').append('<option value="'+i+'">Episode '+i+'</option>');
                episodes.push(i);
            }
        }
        episode_count = x;
    }
    if( episode_count > x ){
        for( let i = episode_count ; i > x ; i-- ){
            $('textarea[name=epsdesc-'+i+']').remove();
            $('option[value='+i+']').remove();
            $('input[name=epstag-'+i+']').remove();
            $('input[name=epslink-'+i+']').remove();
            episodes.splice(i-1, 1);
        }
        episode_count = x;
    }
    
}
function changeEpisode(x){
    $('textarea[name=epsdesc-'+x+']').removeClass('hide');
    $('input[name=epstag-'+x+']').removeClass('hide');
    $('input[name=epslink-'+x+']').removeClass('hide');
    for( let i = 1 ; i <= episode_count ; i++ ){
        if( i != x ){
            $('textarea[name=epsdesc-'+i+']').addClass('hide');
            $('input[name=epstag-'+i+']').addClass('hide');
            $('input[name=epslink-'+i+']').addClass('hide');
        }
    }
}

function changeEntry(x){
    if(x == 'anime'){
        $('#chara').hide();
        $('#anime').show();
    }else{
        $('#chara').show();
        $('#anime').hide();
    }
}

function clearAllCookies(){
    setCookie('filter1',$("#filter1").val(),-30);
    setCookie('filter2',$("#filter2").val(),-30);
    setCookie('type-filter',$("#type-filter").val(),-30);
    location.reload();
}

function switchFilterOption(){
    setCookie('filter1',$("#filter1").val(),30);
    setCookie('filter2',$("#filter2").val(),30);
    setCookie('type-filter',$("#type-filter").val(),30);
}

function applyFilterOption(){
    let filter1 = getCookie('filter1');
    let filter2 = getCookie('filter2');
    let type_filter = getCookie('type-filter');

    $('#filter1 option[value='+filter1+']').prop('selected',true);
    $('#filter2 option[value='+filter2+']').prop('selected',true);
    $('#type-filter option[value='+type_filter+']').prop('selected',true);
}

function href(x){
    window.location.href= x;
}

function changeVideoLink(x){
    $('#video-player').remove();
    $('#video-wrapper').append('<video id="video-player" width="100%" controls><source src="'+x+'" type="video/mp4">Your browser does not support HTML5 video.</video>');
}

function copyToClipboard(x) {
    $('#copy-text').remove();
    $('body').append('<p id="copy-text" class="hide">'+x+'</p>');
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($('#copy-text').text()).select();
    document.execCommand("copy");
    $temp.remove();
  }

function episodeEdit(x){
    $('#ep-edit > button').remove();
    if( x == 1 ){
        $('#ep-edit > .remove').show();
        $('#ep-edit > .remove2').hide();
        $('#ep-edit').append('<button type="button" class="btn btn-danger" style="margin-right:10px;" onclick="episodeEdit(0)" id="cancel">Cancel</button>');
        $('#ep-edit').append('<button type="submit" class="btn btn-primary" name="submit" id="submit">Submit</button>');
    }else{
        $('#ep-edit > .remove').hide();
        $('#ep-edit > .remove2').show();
        $('#ep-edit').append('<button class="btn btn-primary" type="button" onclick="episodeEdit(1)" id="trigger">Update Episode Info</button>');
    }
}

function toggleSlide(x){
    $('#slide-container-'+x).slideToggle();
}

function changePassword(){
    if( $('#password-change').hasClass('hide') ){
        $('#password-change').removeClass('hide');
        $('#password-change > input').prop('required',true);
        $('input[name=password-change]').val('1');
    }else{
        $('#password-change').addClass('hide');
        $('#password-change > input').prop('required',false);
        $('input[name=password-change]').val('0');
    }
}