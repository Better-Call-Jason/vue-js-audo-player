<?php
//echo __DIR__;
?><!DOCTYPE html>
<html lang="en" data-theme="dark">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <link rel="icon" href="img/favicon.png" type="image/png">
        <title></title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
        <link href="https://vjs.zencdn.net/8.6.1/video-js.css" rel="stylesheet" />
        <link rel="stylesheet" href="styles/custom.css">
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://vjs.zencdn.net/8.6.1/video.min.js"></script>
    </head>
    <body>

        <div id="app">

            <h2>{{ name }} {{ displayAlbum }} Player</h2>

            <article class="media-player-card">

                <video id="audio-player"
                       class="video-js"
                       v-bind:style="{ 'background-image': 'url(\'media/' + album + '/cover.jpg\')',
                                      'background-repeat': 'no-repeat',
                                      'background-position': 'center center',
                                      'background-size': 'cover' }"
                       controls
                       width="auto"
                       height="auto"
                       data-setup="{}"
                       playsinline>
                </video>

            </article>

            <article class="grid">

                <button v-for="file in files"
                        @click="playAudio(file)"
                        class="">
                        {{ cleanFileName(file) }}
                </button>

            </article>

        </div>

        <script>
            new Vue({

                 el: '#app',
                 data: {
                     files: [],
                     player: null,
                     name: '',
                     album: '',
                     displayAlbum: '',
                     currentFileIndex: 0,
                     isFirstPlay: true,
                 },

                 created() {
                     let urlParams = new URLSearchParams(window.location.search);
                     if (urlParams.has('name')) {
                         let name = urlParams.get('name');
                         name = name.charAt(0).toUpperCase() + name.slice(1);
                         this.name = name + "'s";
                     }
                    if (urlParams.has('album')) {
                        let album = urlParams.get('album');
                        album = album.replace(/\b\w/g, function(letter) {
                            return letter.toUpperCase();
                        });
                        let displayAlbum = album.replace(/-/g, ' ').replace(/\b\w/g, function(letter) {
                            return letter.toUpperCase();
                        });
                        this.album = album;
                        this.displayAlbum = displayAlbum;
                    }
                    this.updateTitle();
                 },

                 mounted() {
                        this.fetchMediaFiles();
                        this.$nextTick(() => {
                        this.player = videojs('audio-player');
                        this.player.on('ended', this.playNext);

                    });
                },

                 methods: {
                     updateTitle() {
                         document.title = this.name + ' ' + this.displayAlbum + ' Player';
                     },

                     fetchMediaFiles() {
                        axios.get('ajax.php', {
                            params: {
                                album: this.album
                            }
                        }).then(response => {
                            this.files = response.data;
                            if (this.files.length > 0) {
                                 this.playAudio(this.files[0]);
                            }
                        }).catch(error => {
                                console.error('There was an error fetching the media files:', error);
                        });
                    },

                     cleanFileName(file) {
                         return file.replace(/^\d+-/, '').replace(/-/g, ' ').replace(/\.mp3$/, '');
                     },

                     playAudio(file) {
                        this.currentFile = 'media/' + this.album + '/' + file;
                        this.currentFileIndex = this.files.indexOf(file);
                        this.$nextTick(() => {
                            this.player.src({type: 'audio/mp4', src: this.currentFile});
                            this.player.load();
                            this.player.play();
                            if (this.isFirstPlay) {
                                this.player.volume(0.5);
                                this.isFirstPlay = false;
                            }

                        });
                    },

                     playNext() {
                        if (this.currentFileIndex < this.files.length - 1) {
                            this.playAudio(this.files[this.currentFileIndex + 1]);
                        }
                    },
                },

                 beforeDestroy() {
                    if (this.player) {
                    this.player.dispose();
                    }
                }
            });
        </script>

    </body>
</html>
