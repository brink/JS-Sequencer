<? ini_set('display_errors', true);?>
<!DOCTYPE html>
<html>
  <head>
    <title>Beats</title>
  <link rel="stylesheet" media="all" href="reset.css" />
  <!--[if lt IE 8]>
    <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js"></script>
  <![endif]-->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>

  <style>
    body { background-color: #000;}
    section > div {
      width: 600px;
      margin: 0 auto;
      margin-top: 10px;
      clear: both;
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

    .row .step:nth-child(4n) {
      margin-right: 20px;
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
  width: 800px;
  height: 300px;
  overflow-y: auto;
  overflow-x: hidden;
}

#tracks {
  height: 300px;
  width: 600px;
  display: block;
  overflow-x: auto;
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

BeatPlayer = function(container) {
  var container = document.getElementById(container);

  this.getContainer = function() {
    return container;
  }

  // metronome subobject
  this.metronome = new (function() {
      
      this.setRate = function(rate) {
        this.beatLen = Math.round(60000 / rate);
      };

      this.getRate = function() {
        return this.beatLen;
      };

      this.beat = function() {
        var d = new Date();
        
        if ((d.getTime() - this.prevTime) < this.beatLen ) {
          return false;
        } else {
          this.prevTime = d.getTime();
          return true;
        }
      };

      this.reset = function () {
        var d = new Date();
        this.prevTime = d.getTime();
      };


      this.reset();

      return this;
    })();
  // end metronome subobject

  this.currentPlayer = 0;
  this.pos = 0;

  this.players = Array(8);
  this.setTracks();
  this.setSlots();
  this.setRate(135);
  this.drawScore(4,16);
  
  for(var i = 0; i < this.players.length; i++) {
    this.players[i] = document.createElement('audio');
  }
  return this;
  return this;
}

BeatPlayer.prototype.setRate = function(rate) {
  // Rate is in bpm
  this.metronome.setRate(rate);
}

BeatPlayer.prototype.getRate = function() {
  return this.metronome.getRate();
}

BeatPlayer.prototype.setTracks = function(trackCount) {
  this.trackCount = trackCount || 4;
}

BeatPlayer.prototype.setSlots = function(slotCount) {
  if (slotCount > 16) {
    slotCount = 16;
  }
  this.slotCount = slotCount || 16;
}

BeatPlayer.prototype.drawScore = function(tCount,sCount) {
  this.setTracks(tCount);
  this.setSlots(sCount);
  this.buildScore();

  var container = this.getContainer();

  if (container != undefined) {
    if (container.hasChildNodes()) {
      while(container.childNodes.length >= 1) {
        container.removeChild(container.firstChild);
      }
    }
    var section = document.createElement('section');

    var step = document.createElement('div');
    step.classList.add('step');
    step.addEventListener('click', function(elem) { toggleClass(this, "selected"); }, false);
    
    var track = document.createElement('div');
    track.classList.add('row');

    for(var i = 0; i < this.trackCount; i++) {
      s = document.createElement('section');
      var t = track.cloneNode(true);
      
      for(var j = 0; j < this.slotCount; j++) {
        var newStep = step.cloneNode(true);
        newStep.classList.add('step' + j);
        t.appendChild(newStep);
      }

      s.appendChild(t);
      container.appendChild(s);
    }
  }

  return this;
}

BeatPlayer.prototype.buildScore = function() {
  this.rowList = [];
  // Cache the selection for performance
  for (var i = 0; i < this.trackCount; i++) {
    this.rowList[i] = [];

    for (var j = 0; j < this.slotCount; j++) {
      this.rowList[i][j] = 0;
    }
  }
  return this;
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

  //if ((d.getTime() - this.prevTime) <= this.beatLen ) {
  if (!this.metronome.beat()) {
    return;
  }

  for (var i = 0; i < rows.length; i++) {
    toggleClass(document.getElementsByClassName('step' + (this.pos)), 'glow',this.getRate()-150);

    if (this.rowList[i][this.pos]) {
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
  this.metronome.reset();

  this.pos = 0;
  this.currentPlayer = 0;
}

//----------------------- 


  function toggleClass(elem, className, fade) {

    if (elem.length > 1) {
      for(var i = 0; i < elem.length; i++) {
        toggleClass(elem[i], className, fade);
      }
    } else {
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
  }

    function togglePlay() {
      
      if (window.playing) {
        window.clearInterval(window.playing);
        window.playing = false;
      } else {

        window.playing = setInterval(playCurrentSlot, 50);
        audio.reset();
      }
    }

    function playCurrentSlot() {
      window.audio.playCurrentSlot();
    }

    function init() {
      this.audio = new BeatPlayer('tracks');
      
      /*  list[i].addEventListener('click', function(elem) {
          toggleClass(this, "selected");
    }, false); */
      rows = document.getElementsByClassName('row');
        rows[0].sound_file = 'tish1.wav';
        rows[1].sound_file = 'tish.mp3';
        rows[2].sound_file = 'tish.ogg';
        rows[3].sound_file = 'boom1.wav';
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
  </div>
</div>
</section>
  </body>
</html>
