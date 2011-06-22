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
      -webkit-box-shadow: 0px 0px 15px #efd;
      box-shadow: 0px 0px 15px #efd;
      background-color: #efd !important;
      border: 1px solid #efd !important;
    }

    .glow {
      -moz-box-shadow: 0px 0px 5px #efd;
      -webkit-box-shadow: 0px 0px 5px #efd;
      box-shadow: 0px 0px 5px #efd;
      background-color: #aaa !important;
    }
    .selected.glow {
      background-color: #d35 !important;
    }

#player { 
  height: 300px;
}
#player,#controls {
  padding: 1em;
  border-radius: 20px;
  border: 3px solid #222;
}
#tracks {
  width: 600px;
  float: left;
clear: none;
}
#controls {
  margin-top: 8px;
  float: right;
  text-align: center;
  width: 100px;
}
  </style>
  <script>

    BeatPlayer = function() {
      
      return this;
    }
BeatPlayer.prototype.setRate = function(rate) {
  // Rate is in bpm
  this.beatLen = Math.round(60000 / rate);
}

BeatPlayer.prototype.getRate = function() {
  return Math.round(60000 / this.beatLen);
} 
BeatPlayer.prototype.init = function() {
  this.players = Array(1,2,3,4,5,6,7);
  this.trackSlots = 16;
  this.rowList = document.getElementsByClassName('row');
  
  // Cache the selection for performance
  for (var i = 0; i < this.rowList.length; i++) {
    this.rowList[i].stepList = this.rowList[i].getElementsByClassName('step');
  }
  for(var i = 0; i < this.players.length; i++) {
    this.players[i] = document.createElement('audio');
  }
}

BeatPlayer.prototype.getFreePlayer = function() {
  if (++this.currentPlayer >= this.players.length) {
    this.currentPlayer = 0;
  }
  return this.players[this.currentPlayer];
}

BeatPlayer.prototype.playCurrentSlot = function() {
  if (this.pos >= this.trackSlots) {
    this.pos = 0;
  }

  var d = new Date();
  if ((d.getTime() - this.prevTime) <= this.beatLen ) {
    return;
  } else {
  }

  this.prevTime = d.getTime();

  for (var i = 0; i < rows.length; i++) {
    toggleClass(this.rowList[i].stepList[this.pos], 'glow',this.beatLen-50);

    if (this.rowList[i].stepList[this.pos] && this.rowList[i].stepList[this.pos].classList.contains('selected')) {
      player = this.getFreePlayer();

      player.src = this.rowList[i].sound_file;
      player.play();
    }
  }
  this.pos++;
}

BeatPlayer.prototype.increaseTempo = function(val) {
  this.setRate(this.getRate() + val);
}

BeatPlayer.prototype.decreaseTempo = function(val) {
  this.setRate(this.getRate() - val);
}

BeatPlayer.prototype.reset = function() {
  var d = new Date();
  this.prevTime = d.getTime();

  this.pos = 0;
  this.currentPlayer = 0;
}

//----------------------- 


  function toggleClass(elem, className, fade) {

      if (elem.classList.contains(className)) {
        elem.classList.remove(className);
      } else {
        elem.classList.add(className);
        if (fade) {
          setTimeout(function() {
            toggleClass(elem,className);
          }, fade);
        }
      }
    }

    function togglePlay() {
      
      if (window.playing) {
        window.clearInterval(window.playing);
        window.playing = false;
      } else {
        window.playing =         setInterval("audio.playCurrentSlot()", 50);
        audio.reset();
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
      this.audio = new BeatPlayer();
      this.audio.setRate(135);
      this.audio.reset();
      audio.init();
    }

  </script>
</head>
  <body onload="init()">
<section>
<div id ="player">
  <div id="controls">
    <input type="button" onclick="togglePlay();" value="play"/><br/>
    <!-- input type="range" name="tempo" min="60" max="260" /><br/ -->
    <input type="button" onclick="audio.increaseTempo(5);" value="+" />
    <input type="button" onclick="audio.decreaseTempo(5);" value="-" /><br/>
    <input id="x-tempo" size="4" type="text" disabled="true" name="tempo" value="---" style="clear: none;"/>
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
