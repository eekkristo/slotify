var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false;
var userLoggedIn;
var result;
var playlistParsed;
var songId;

$(document).click(function(click){
    var target = $(click.target);

    if (!target.hasClass("item") && !target.hasClass("optionsButton")) {
        hideOptionsMenu();
    }
});

$(window).scroll(function(){
    hideOptionsMenu();
});

$(document).on("change", "select.playlist", function() {
    var select = $(this);
    var playlistId = select.val();
    console.log(songId);
    console.log(playlistId);
    $.post("/ajax/addSongToPlaylist", {playlistId: playlistId, songId: songId})
    .done(function(error) {

        if (error != "") {
            alert(error);
            return;
        }
        hideOptionsMenu();
        select.val("");

    })
});


function openPage(url) {


    var encodedUrl = encodeURI(url);
    $("#mainContent").load(encodedUrl);
    //("body").scrollTop(0);
    history.pushState(null, null, url);
    //console.log(history.pushState(null, null, url));
    //console.log(userLoggedIn);
}

function updateEmail(emailClass) {
    var emailValue = $("." + emailClass).val();

    // TODO: Implement update email and update password change
    $.post()
}

function formatTime(seconds) {
    var time = Math.round(seconds);
    var minutes = Math.floor(time / 60);
    var seconds = time - (minutes * 60);

    var extraZero = (seconds < 10) ? "0" : "";

    return minutes + ":" + extraZero + seconds;
}

function updateTimeProgressBar(audio) {
    $(".progressTime.current").text(formatTime(audio.currentTime));
    $(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));

    var progress = audio.currentTime / audio.duration * 100;
    $(".playbackBar .progress").css("width", progress + "%");

}

function updateVolumeProgressBar(audio) {
    var volume = audio.volume  * 100;
    $(".volumeBar .progress").css("width", volume + "%");
}

function playFirstSong(){
    setTrack(tempPlaylist[0], tempPlaylist, true);
}

function createPlaylist(username){
    var popup = prompt("Please enter the name of your playlist");

    if (popup != null) {
        $.post("/ajax/createPlaylist", {name: popup, username:username })
        .done(function(){
            
            openPage("/playlist");
        });
    }
}

function getPlaylists() {
   
        // If result is empty we need postrun ajax run only
        // After that we should store this into an variable so we don't query the database constantly. 
        // TODO:: Make this better. Right now this kind of is all hard coded into here and due to that really hard to add more options
        // FIXME: There is a bug if you add a song from your current playlist where you are another song the UI will flip out and render double your playlist
        // Should be fixed by forcing a ajax reload on the playlist if it is the same
        // FIXME: When you create a new playlist it will not be visible in the add to playlist. This is due to result is not empty. 
        // To fix this we need to clear the result when new playlist is created
        // TODO: Implement a delete song from playlist. Currently due to clearing optionsMenu we can't display delete from playlist function.
        
        if (!result || result.length === 0) {

            $.post("/ajax/getPlaylists", {getPlaylists: 2})
            .done(function(data) {
                console.log("AJAX CALL");
                playlistParsed = JSON.parse(data);

                $("<select class='item playlist'> \
                                <option value=''>ADD TO PLAYLIST</option> \
                            ").appendTo(".optionsMenu");

                if (data) {
                    result = playlistParsed;
                    playlistParsed.forEach(playlist => {
                
                        $("<option value='"+ playlist.id +"'>" + playlist.name + "</option> \
                            </select> \
                            ").appendTo(".item");
        
                        console.log(playlist.name);
                    });
                }  
            });
        $(".optionsMenu").empty();  
         
        } 
}

function deletePlaylist(playlist_id) {
    var prompt = confirm("Are you sure you sure you want to delete this playlist");

    if (prompt) {
        
        $.post("/ajax/deletePlaylist", {playlistId: playlist_id})
        .done(function(error) {
            if (error != "") {
                alert(error)
                return;
            }

            openPage("/playlist");
        });
    }
}

function removeFromPlaylist(button, playlist_id) {
    songId = $(button).prevAll(".songId").val();

    if (prompt) {
        
        $.post("/ajax/deleteSong", {playlistId: playlist_id, song_id: song_id})
        .done(function(error) {
            if (error != "") {
                alert(error)
                return;
            }

            openPage("/playlist/"+user_id+"/"+playlist_id);
        });
    }

}

function hideOptionsMenu() {
    var menu = $(".optionsMenu");

        menu.css({"display": "none"});
    
}

function showOptionsMenu(button) {
    songId = $(button).prevAll(".songId").val();
    var menu = $(".optionsMenu");
    var menuWidth = menu.width();
    menu.find(".songId").val(songId);

    var scrollTop = $(window).scrollTop(); // Distance from top of window to top of document
    var elementOffset = $(button).offset().top; // Distance from top documentElement

    var top = elementOffset - scrollTop;
    var left = $(button).position().left;

    menu.css({ "top": top + "px", "left": left - menuWidth + "px", "display": "inline" });

    getPlaylists();
}



function Audio() {

    this.currentlyPlaying;
    this.audio = document.createElement('audio');

    this.audio.addEventListener('ended', function() {
        nextSong();
    });

    this.audio.addEventListener('canplay', function() {
        var duration = formatTime(this.duration);
        $(".progressTime.remaining").text(duration);
    });

    this.audio.addEventListener('timeupdate', function() {
        if (this.duration) {
            updateTimeProgressBar(this);
        }
    });

    this.audio.addEventListener("volumechange", function() {
        updateVolumeProgressBar(this);
    });

    this.setTrack = function (track) {
        this.currentlyPlaying = track;
        this.audio.src = "/" + track.path;
    }

    this.play = function () {
        this.audio.play();
    }

    this.pause = function () {
        this.audio.pause();
    }

    this.setTime = function (seconds) {
        this.audio.currentTime = seconds;
    }
}