<!-- Edit images from /public/storage/images -->
<template>
    <div v-if="this.$gate.isAdmin()" class="row flex-xl-nowrap">
<!-- The following <div> contain the tools for editing a photo -->
        <div class="col-2">
<!-- The following <button> is shown if parameters croper and resizer are set to false. The click event set the parameter croper to true-->
            <button class="w-100 btn btn-secondary" v-if="!croper && !resizer" @click="croper = true">Изрежи</button>
<!-- The following <div> is shown if parameter croper is set to true.
    The input type radio swichs the croperMethod parameter value to the input value-->
            <div class="input-group-text" v-if="croper"><input type="radio" v-model="croperMethod" name="resolution" value="free">
            &nbsp;Свободно</div>
<!-- The following <div> is shown if parameter croper is set to true.
    The input type radio swichs the croperMethod parameter value to the input value-->
            <div class="input-group-text" v-if="croper"><input type="radio" v-model="croperMethod" name="resolution" value="16:9">
            &nbsp;16:9</div>
<!-- The following <div> is shown if parameter croper is set to true.
    The input type radio swichs the croperMethod parameter value to the input value-->
            <div class="input-group-text" v-if="croper"><input type="radio" v-model="croperMethod" name="resolution" value="4:3">
            &nbsp;4:3</div>
<!-- The following <button> is shown if parameter croper is set to true. The click event calls the "cropImg" method-->
            <button class="w-100 btn btn-secondary mt-1" v-if="croper" @click="cropImg()">Изрежи</button>
<!-- The following <button> is shown if parameters croper and resizer are set to false the click event set the parameter resizer to true-->
            <button class="w-100 btn btn-secondary mt-1" v-if="!croper && !resizer" @click="resizer = true">Смали</button>
<!-- The following <p> is shown if parameter resizer is set to true and  show real width of the photo.-->
            <p v-if="resizer" class="dwhite">Дължина - {{this.photoWidth}}px.</p>
<!-- The following <p> is shown if parameter resizer is set to true and  show real height of the photo.-->
            <p v-if="resizer" class="dwhite">Височина - {{this.photoHeight}}px.</p>
<!-- The following <button> is shown if parameters croper and resizer are set to false. The click event calls "rotateImg" method. -->
            <button class="w-100 btn btn-secondary mt-1" v-if="!croper && !resizer" @click="rotateImg()">Завърти</button>
<!-- The following <button> is shown if parameters croper and resizer are set to false. The click event calls "mirrorImgX" method. -->
            <button class="w-100 btn btn-secondary mt-1" v-if="!croper && !resizer" @click="mirrorImgX()">Огледало <i class="fas fa-arrows-alt-h"></i></button>
<!-- The following <button> is shown if parameters croper and resizer are set to false. The click event calls "mirrorImgY" method. -->
            <button class="w-100 btn btn-secondary mt-1" v-if="!croper && !resizer" @click="mirrorImgY()">Огледало <i class="fas fa-arrows-alt-v"></i></button>
<!-- The following <button> is shown if parameters croper and resizer are set to true. The click event calls "closeMenus" method. -->
            <button button="w-100 btn btn-secondary mt-1" v-if="croper || resizer" @click="closeMenus()">Затвори</button>
        </div>
<!-- The following <div> contain logic to set the view size of the photo, the photo and the croper.-->
        <div class="col-10 pl-0 pr-0">
<!-- The following <div> contain logic to set the view size of the photo. The input mousemove event calls the "setViewSize" method.-->
            <span class="col-12 dwhite pl-4 bg-secondary">размер на изгледа : <input class="col-8" type="range" min="1" max="100" @mousemove="setViewSize()" v-model="photoSize" id="myRange">
            {{this.photoSize}}%</span><br>
<!-- The following <div> contain the photo to edit. -->
            <div class="resizer-container col-12 pl-0 pr-0"
            :style="'height:' + this.photoHeight + 'px; width:'+ this.photoWidth + 'px;'">
                <img id="output" @load="getSize" :src="'/storage/images/'+this.$route.params.photo" alt="your image"
                :style="'height:'+this.photoHeight +'px;' +
                ' width:' + this.photoWidth + 'px;'"/>
<!-- The following <div> contain the croper to crop a part of the image.-->
                <div v-show="this.croper" id="croper" :style="'height:' + this.croperHeight + 'px; width:' + this.croperWidth + 'px'" @mouseup="setCroper" draggable="true">
                <i class="fas fa-arrows-alt" id="cropercursor"></i></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                photo : null, // the photo title
                croper : false, // croper condition
                resizer : false, // resizer condition
                keepHeight : null, // the real hight of the photo
                keepWidth : null, // the real width of the photo
                photoHeight : null, // the view height of the photo
                photoWidth : null, // the view width of the photo
                photoSize : '100', // the size of the photo in %
                croperTop : null, // the top position on the croper against the image
                croperLeft : null, // the left position on the croper against the image
                croperHeight : 90, // the croper height
                croperWidth : 160, // the croper width
                croperMethod : 'free', // the croper aspect ratio
                form: new Form({ // //instantiate a new Form object
                    photo: this.$route.params.photo, // the photo title to be send to the backend
                    category: this.$route.params.category,
                    method: null, // the method to to be send to the backend
                })
            }
        },
        methods: {
            getSize() { // get the size of the photo
                this.keepHeight = $('#output').height()
                this.keepWidth = $('#output').width()
                this.photoHeight = $('#output').height()
                this.photoWidth = $('#output').width()
                let drager = new DragElement()
                drager.dragElement(document.getElementById("croper"), document.getElementById("cropercursor"))
            },
            setCroper() { // croper logic
                let croper = document.getElementById("croper") //get croper element by id
                this.form.croperTop = parseInt(croper.style.top) //get croper top from style and conver from string to integer
                this.croperTop = this.form.croperTop
                this.form.croperLeft = parseInt(croper.style.left) //get croper left from style and conver from string to integer
                this.croperLeft = this.form.croperLeft
                this.form.croperWidth = parseInt(croper.style.width) //get croper width from style and conver from string to integer
                this.croperWidth = this.form.croperWidth
                if(this.croperMethod == "4:3") { // logic to set the cropper height to 4:3 aspect ratio
                    this.form.croperHeight = this.form.croperWidth / 4 * 3
                    this.croperHeight = this.form.croperHeight
                } else if (this.croperMethod == "16:9") { // logic to set the cropper height to 16:9 aspect ratio
                    this.form.croperHeight = this.form.croperWidth / 16 * 9
                    this.croperHeight = this.form.croperHeight
                } else {
                    this.form.croperHeight = parseInt(croper.style.height, 10) //get croper height from style and conver from string to integer
                    this.croperHeight = this.form.croperHeight
                }
                if(this.photoSize != '100') { // if the user change the view size of the photo performs the logic
                        this.form.croperTop = this.croperTop /100*(200 - parseInt(this.photoSize))
                        this.form.croperLeft = this.croperLeft /100*(200 - parseInt(this.photoSize))
                        this.form.croperHeight = this.croperHeight /100*(200 - parseInt(this.photoSize))
                        this.form.croperWidth = this.croperWidth /100*(200 - parseInt(this.photoSize))
                }
            },
            setViewSize() { // logic to correctly set the view size of the image
                let photo = parseInt(this.photoSize)
                let text = ''
                if (photo < 10) {
                    text = '0.0'+ photo
                } else if(photo >=10 && photo <= 99 ) {
                    text = '0.'+ photo
                } else {
                    text = '1'
                }
                this.photoHeight = this.keepHeight * parseFloat(text)
                this.photoWidth = this.keepWidth * parseFloat(text)
            },
            rotateImg() { // send data to backend to rotate the image
                this.$loadStart()
                this.form.method = 'rotate'
                setTimeout(() => {
                    if(this.$gate.isAdmin()) {
                        this.form.post('/api/editImg')
                        .then(function (response) {
                            window.location.assign("http://club-chernomorecz.com/%D0%A1%D0%BD%D0%B8%D0%BC%D0%BA%D0%B8/"+response.data+"/%D0%9A%D0%B0%D1%82%D0%B5%D0%B3%D0%BE%D1%80%D0%B8%D1%8F/news")
                        })
                        .catch(() => {
                            swal({
                                type: 'error',
                                title: 'Възникна грешка',
                                showConfirmButton: false,
                                timer: 2000,
                                reload: setTimeout(() => {
                                    /* location.reload() */
                                }, 2000)
                            })
                        })
                    }
                },100)
            },
            cropImg() { // send data to backend to crop the image
                this.$loadStart()
                this.form.method = 'crop'
                setTimeout(() => {
                    if(this.$gate.isAdmin()) {
                        this.form.post('/api/editImg')
                        .then(function (response) {
                            window.location.assign("http://club-chernomorecz.com/%D0%A1%D0%BD%D0%B8%D0%BC%D0%BA%D0%B8/"+response.data+"/%D0%9A%D0%B0%D1%82%D0%B5%D0%B3%D0%BE%D1%80%D0%B8%D1%8F/news")
                        })
                        .catch(() => {
                            swal({
                                type: 'error',
                                title: 'Възникна грешка',
                                showConfirmButton: false,
                                timer: 2000,
                                reload: setTimeout(() => {
                                    /* location.reload() */
                                }, 2000)
                            })
                        })
                    }
                },100)
            },
            mirrorImgX() { // send data to backend to flip the image horizontally
                /* this.$loadStart() */
                this.form.method = 'flipX'
                setTimeout(() => {
                    if(this.$gate.isAdmin()) {
                        this.form.post('/api/editImg')
                        .then(function (response) {
                            window.location.assign("http://club-chernomorecz.com/%D0%A1%D0%BD%D0%B8%D0%BC%D0%BA%D0%B8/"+response.data+"/%D0%9A%D0%B0%D1%82%D0%B5%D0%B3%D0%BE%D1%80%D0%B8%D1%8F/news")
                        })
                        .catch(() => {
                            swal({
                                type: 'error',
                                title: 'Възникна грешка',
                                showConfirmButton: false,
                                timer: 2000,
                                reload: setTimeout(() => {
                                    location.reload()
                                }, 2000)
                            })
                        })
                    }
                },100)
            },
            mirrorImgY() { // send data to backend to flip the image vertically
                this.$loadStart()
                this.form.method = 'flipY'
                setTimeout(() => {
                    if(this.$gate.isAdmin()) {
                        this.form.post('/api/editImg')
                        .then(function (response) {
                            window.location.assign("http://club-chernomorecz.com/%D0%A1%D0%BD%D0%B8%D0%BC%D0%BA%D0%B8/"+response.data+"/%D0%9A%D0%B0%D1%82%D0%B5%D0%B3%D0%BE%D1%80%D0%B8%D1%8F/news")
                        })
                        .catch(() => {
                            swal({
                                type: 'error',
                                title: 'Възникна грешка',
                                showConfirmButton: false,
                                timer: 2000,
                                reload: setTimeout(() => {
                                    /* location.reload() */
                                }, 2000)
                            })
                        })
                    }
                },100)
            },
            closeMenus() { // reset all the data by reloading the page
                location.reload()
            },
        },
        mounted() {

        }
    }
</script>
