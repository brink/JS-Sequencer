<? ini_set('display_errors', true);?>
<!DOCTYPE html>
<html>
  <head>
    <title>Beats</title>
  <link rel="stylesheet" media="all" href="reset.css" />
<!--[if lt IE 8]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js"></script>

<![endif]-->
  <style>
    body { background-color: #000;}
    section > div {
      margin: 0 auto;
      width: 800px;
      margin-top: 10px;
      clear: both;
    }
    .section div.row {
      height: 25px;
    
    }
    .step-space, .step {
      display: block;
      float: left;
      width: 10px;
      height: 20px;
    }
    .step:hover {
      background-color: orange;
    }
    .step:before {
      -webkit-transition: all 0.1s linear;
      -moz-transition: all 0.1s linear;
      transition: all 0.1s linear;
      -moz-transform-origin: center;
      transform-origin: center;
      content: '';
      width: 20px;
      height: 20px;
      border-radius: 100px;
    }

    .step:hover:before {
      -webkit-box-shadow: 0px 0px 15px #fff;
      -moz-box-shadow: 0px 0px 15px #fff;
      box-shadow: 0px 0px 15px #fff;
      display: block;
      width: 20px;
      height: 20px;
      z-index: -22;
    }

    .step {
      width: 20px;
      border: 1px solid #333;
      border-radius: 55px;
      background-color: #444;
      margin: 5px;
    }
    .selected {
      -moz-box-shadow: 0px 0px 15px #efd;
      box-shadow: 0px 0px 15px #efd;
      background-color: #efd !important;
      border: 1px solid #efd !important;
    }

    .glow {
      -moz-box-shadow: 0px 0px 5px #efd;
      background-color: #aaa !important;
    }
    .selected.glow {
      background-color: #d35 !important;
    }

#player { 
  padding: 1em;
  height: 300px;
  border-radius: 20px;
  border: 3px solid #111;
}
#tracks {
  width: 600px;
  float: left;
clear: none;
}
#controls {
  margin-top: 35px;
  margin-right: 35px;
  float: right;
}
  </style>
  <script>

    BeatPlayer = function() {
      var players = Array(0,1,2,3,4,5,6,7);
      var trackSlots = 16;

      this.init = function() {
        this.sync = false;
        this.stepList = document.getElementById('A').getElementsByClassName('step');
        this.rowList = document.getElementsByClassName('row');
        this.position = 0;
        this.currentPlayer = 0;
        this.bpm = 120;
        this.setRate(this.bpm);

        for(var i = 0; i < players.length; i++) {
          players[i] = document.createElement('audio');
        }
      }
      this.playCurrentSlot = function() {
        if (this.sync) {
          return; 
        }
        this.sync = true;
        var d = new Date();
        var currentTime = d.getTime();
        var totalBeats = 0;

        while (this.prevTime + this.beatLen >= d.getTime()) {
//          this.prevTime += this.beatLen;
        } 

        if (!this.position) {
          console.log('no position');
        }
        if (this.stepList[this.position].classList.contains('selected')) {
          console.log('beat');
          player = this.getFreePlayer();

          player.src = "boom1.wav";
          player.play();
        }

        this.position++;

        if (this.position >= trackSlots) {
          this.position = 0;
          console.log('reset');
        }
        this.sync = false;
      }
      this.getFreePlayer = function() {
        if (++this.currentPlayer >= players.length) {
          this.currentPlayer = 0;
        }
        return players[this.currentPlayer];
      }
      this.setRate = function(rate) {
        // Rate is in bpm
        this.beatLen = Math.round(60000 / rate)
      }
    }

BeatPlayer.prototype.increaseTempo = function() {
  this.setRate(this.bpm+1);
  this.reset();
}
BeatPlayer.prototype.decreaseTempo = function() {
  this.setRate(this.bpm-1);
  this.reset();
}


BeatPlayer.prototype.reset = function() {
  var d = new Date();
  this.prevTime = d.getTime();
}
        
      BeatPlayer.prototype.getBeats = function() {
        var d = new Date();
        var currentTime = d.getTime();
        var totalBeats = 0;

        while (this.prevTime + this.beatLen <= currentTime) {
          this.prevTime += this.beatLen;
          totalBeats++;
        }

        return totalBeats;
      }


      BeatPlayer.prototype.playBySlot = function() {
        for (var i = 0; i < this.rowList.length; i++) {
          var row_name = this.rowList[i].id;

          if (this.rowList[index].classList.contains('selected')) {
            player = audio.getFreePlayer();

            player.src = list[index].parentNode.sound_file;//"boom";
            if (player.currentTime > player.startTime ) {
              player.pause();
              player.currentTime = 0;
              player.play();
            } else {
              player.play();
            }
          }
        }
      }
    




//----------------------- 
    var audio = new BeatPlayer();
    audio.setRate(135);
    audio.reset();



    function toggleClass(elem, className, fade) {
      if (elem.classList.contains(className)) {
        elem.classList.remove(className);
      } else {
        elem.classList.add(className);
        if (fade) {
          setTimeout(function() {
            toggleClass(elem,className);
          }, audio.global_rate);
        }
      }
    }
    
    
    function togglePlay() {
      audio.init();
      if (window.playing) {
        window.clearInterval(window.playing);
        window.clearTimeout(window.all);
        window.playing = false;
      } else {
        runList();
//        window.playing = setInterval(runList,(16 * audio.global_rate));
      }
    }

    function init() {
      list = document.getElementsByClassName('step');
      for(var i = 0; i< list.length; i++) {
        list[i].addEventListener('click', function(elem) {
          toggleClass(this, "selected");
        }, false);
      }
      rows = document.getElementsByClassName('row');
        rows[0].sound_file = 'tish1.wav';
        rows[1].sound_file = 'tish.mp3';
        rows[2].sound_file = 'tish.ogg';
        rows[3].sound_file = 'boom1.wav';
      audio.init();
    }

    function runList() {
      row_list = document.getElementsByClassName('row');
      for (var j = 0; j < row_list.length; j++) {
        var row_name = row_list[j].id;
        list = document.getElementById(row_name).getElementsByClassName('step');
  
        for (var i = 0; i < list.length; i++) {
          offset = (audio.global_rate * (i+1) - audio.global_rate);
          setTimeout("playBeat(" + i + ",'" + row_name + "')",offset);
        }
      }
    }

    function playBeat(index,row) {
      list = document.getElementById(row).getElementsByClassName('step');
      toggleClass(list[index], "glow",true);
      if (list[index].classList.contains('selected')) {
        player = audio.getFreePlayer();

        player.src = list[index].parentNode.sound_file;//"boom";
        if (player.currentTime > player.startTime ) {
          player.pause();
          player.currentTime = 0;
          player.play();
        } else {
          player.play();
        }
      }
    }
  </script>
</head>
  <body onload="init()">
<section>
<div id ="player">
  <div id="controls">
    <input type="button" onclick="togglePlay();" value="play"/>
    <input type="button" onclick="audio.increaseTempo();" value="+" />
    <input type="button" onclick="audio.decreaseTempo();" value="-" />
</div>
<div id="tracks">
    <? for($i = 'A'; $i <= 'D'; $i++) { ?>
    <section>
      <div class="row" id="<?= $i ?>">
        <? for($j = 1; $j<= 16; $j++) { ?>
          <div id="<?= $j ?>" class="step step<?=$j?>"></div>
          <? if ($j % 4 == 0) { ?>
            <div class="step-space"></div>
          <? } ?>
        <? } ?>
      </div>
    </section>
    <? } ?>
</div>
</div>
</section>
  </body>
</html>
