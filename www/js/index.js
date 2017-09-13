/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
 
 function onLoad() 
 {
      alert('sdfds');
 } 
 

var app = {
    // Application Constructor
    initialize: function() {
        document.addEventListener('app', this.onDeviceReady.bind(this), false);
        var ht=document.getElementById("htmlid");  
        ch=ht.clientHeight;  
        cw=ht.clientWidth; 
        //alert(hh);       
        document.getElementById("page1").style.height=ch+'px';                       
        
        document.getElementById("map").style.width=cw+'px';
        
        var maph=ch-32-100;
        document.getElementById("map").style.height=maph+'px';   
        ph=document.getElementById("content").clientHeight-maph;
        document.getElementById("predp").style.width=cw+'px';
        //document.getElementById("predp").style.height=;
        //alert(ph);
    //}
    },

    // deviceready Event Handler
    //
    // Bind any cordova events here. Common events are:
    // 'pause', 'resume', etc.
    onDeviceReady: function() {
        this.receivedEvent('app');
        //alert('22'); 
        
    },      

    // Update DOM on a Received Event
    receivedEvent: function(id) {
        var parentElement = document.getElementById(id);
        var listeningElement = parentElement.querySelector('.listening');
        var receivedElement = parentElement.querySelector('.received');

        listeningElement.setAttribute('style', 'display:none;');
        receivedElement.setAttribute('style', 'display:block;');

        console.log('Received Event: ' + id);
    }
};

app.initialize();