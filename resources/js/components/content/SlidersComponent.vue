<!--Displays the contents of each slider. When the logged in user is administrator the content editing tools are shown. -->
<template>
<div class="row flex-xl-nowrap">
<!--The following <div> is shown only if "adminMode" parameter is set to true.
    He holds <a> with a click event that open the form to insert new records to the DB.
    On the click event the prevent method was called to prevent the default behavior of the <a>
    The href attribute is empty to keep the pointer:cursor behavior of the <a> -->
    <a href="" class="float-right" v-if="adminMode" @click.prevent="showModal()"><i class="fas fa-file-import fa-3x green"></i></a>
    <div class="col-12 col-lg-9 col-md-8">
        <div>
<!-- The following <div> holds the navigation of the slider. The color of the <i> change by methods instead of CSS class:hover-->
            <div class="sliderNav d-flex justify-content-between" v-once>
                <i @mouseout="leftArrowHover()" @mouseover="leftArrowHover()" class="fas fa-angle-double-left fa-3x green" @click="leftArrow()"></i>
                <i @mouseout="rightArrowHover()" @mouseover="rightArrowHover()" class="fas fa-angle-double-right fa-3x green" @click="rightArrow()"></i>
            </div>
            <div class="sliderContiner">
<!--The following <div> is multiplied by Vuejs v-for directive for each record form "sliders" parameter.  -->
                <div  v-for="(slider, index) in sliders" :key="slider.id" :class="{ active: index==0, slider}">
<!-- The following <div> is shown if sliderMode parameter is true -->
                    <div v-if="sliderMode">
                        <h3 class="text-center dwhite">{{ slider.title }}</h3>
                        <a href="" @click.prevent="choiseSlider()"> <!-- toggle sliderMode parameter-->
                        <img :src="'/storage/images/' + slider.photos" :alt="slider.title">
                        </a>
                    </div>
<!-- The following <div> is shown if sliderMode parameter is false -->
                    <div v-if="!sliderMode">
                        <h3 class="text-center dwhite">{{ slider.title }}</h3>
                        <p class="dwhite d-inline-block pr-2 viewSliderDescription">
                            <img class="w-50 float-left" @click="choiseSlider()" :src="'/storage/images/' + slider.photos" :alt="slider.title">
                            {{ slider.description }}
                        </p>
<!-- The following <div> is shown if adminMode parameter is true (the user is administrator)-->
                        <div v-if="adminMode" class="p-1 d-inline-block w-100">
                            <hr class="mb-0 mt-0">
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a>
    and wich calls "editSlider" method passing the slider object to it. -->
                            <a href="" @click.prevent="editSlider(slider)"><i class="fas fa-edit yellow"></i>&#160;</a>
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a>
    and wich calls "deleteSlider" method passing the slider id to it. -->
                            <a href="" v-if="adminMode" @click.prevent="deleteSlider(slider.id)"><i class="fas fa-trash red"></i>&#160;</a>
<!--The following <a> has binded href atribute to imagerComponent and passing the photo title to it.  -->
                            <a :href="'/Снимки/' + slider.photos"><i class="fas fa-cut blue"></i>&#160;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!--The following <div> is created based on "bootstrap" and "vForm" packages -->
        <div class="modal fade" id="sliderModal" tabindex="-1" role="dialog" aria-labelledby="sliderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-if="!editMode" class="modal-title" id="sliderModalLabel">Добавяне на слайд</h5>
                        <h5 v-if="editMode" class="modal-title" id="sliderModalLabel">Промяна на слайд {{ this.form.id }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
<!-- The following <form> when submitted calls "updateSlider" or "createSlider" method based of the editMode parameter condition.-->
                    <form @submit.prevent="editMode ? updateSlider() : createSlider()" id="slidersForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Заглавие:</label>
                                <input v-model="form.title" type="text" name="title"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('title') }" placeholder="Заглавие" required>
                                <has-error :form="form" field="title"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Снимка:</label>
                                <input type="file" @change="onFileSelect" name="photos" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Описание:</label>
                                <textarea v-model="form.description" type="text" name="description"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('description') }" placeholder="Описание" required></textarea>
                                <has-error :form="form" field="description"></has-error>
                            </div>
                        </div>
<!-- The following <div> holds buttons based on editMode parameter condition and button to clear the form with that call clearForm method -->
                        <div class="modal-footer">
                            <button type="button" @click="clearForm()" class="btn btn-danger">Изчисти</button>
                            <button type="submit" v-show="editMode" class="btn btn-primary">Промени</button>
                            <button type="submit" v-show="!editMode" class="btn btn-success">Добави</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Затвори</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Display the globaly registrated vuejs announcement component. -->
    <announcement-component></announcement-component>
</div>
</template>

<script>
    export default {
        data() {
            return {
                adminMode: false, // swich to show administrator content
                sliders: null, // holds the records from the DB
                sliderInterval : setInterval(this.runInterval, 6000), // display different slider every 6 seconds
                sliderMode : true, // swich the slider mode
                editMode : false, // swich the edit mode
                form: new Form({ //instantiate a new Form object
                    id: null,   // id of DB record
                    title : null, // title of DB record
                    photos : null, // image of/for the DB record
                    description : null, // description of/for the DB record
                }),
            }
        },
        methods :{
             clearForm() { // clear the form based on editMode parameter condition keep data
                if (this.editMode) {
                    this.form.originalData.id = this.form.id
                    this.form.originalData.photos = this.form.photos
                    this.form.reset();
                } else if (!this.editMode){
                    this.form.originalData.id = null
                    this.form.originalData.photos = null
                    this.form.reset();
                }
            },
            getSliders() { //request to the backend to get the records
                axios.get("/api/Sliders")
                .then(( data ) => {this.sliders = data.data});
            },
            choiseSlider() { // change the way that shows the content
                if (this.sliderMode) {
                this.sliderMode = false;
                clearInterval(this.sliderInterval);
                } else {
                    this.sliderMode = true;
                    this.sliderInterval = setInterval(this.runInterval, 6000);
                }
            },
            showModal(){ // display the form to insert DB records
                this.editMode = false
                $("#sliderModal").modal("show");
            },
            onFileSelect(event) { //instantiate new FileReader object for the selected file
                let file = event.target.files[0];
                this.form.photos = event.target.files[0];
                let reader = new FileReader();
                reader.onloadend = () => {
                   this.form.photos = reader.result
                }
                reader.readAsDataURL(file)
            },
            createSlider(){ //request to the backend to create a new record
                if(this.$gate.isAdmin()) {
                    this.form.post('/api/Sliders')
                    .then(() => {
                        toast({type: 'success', title: 'Успешно добавихте нов слайд!'})
                        $('#sliderModal').modal('hide');
                        this.getSliders()
                        this.form.reset();
                    })
                    .catch(() => {

                    })
                }
            },
            editSlider(data){ // display the form to edit DB records
                this.editMode = true;
                $("#sliderModal").modal("show");
                this.form.fill( data );
            },
            updateSlider() { // request to the backend to update specific record
                if(this.$gate.isAdmin()) {
                    this.form.patch('/api/Sliders/' + this.form.id)
                    .then(() => {
                        $('#sliderModal').modal('hide');
                        toast({type: 'success', title: 'Промяната приложена успешно!'})
                        this.getSliders();
                    })
                    .catch(() => {
                    })
                }
            },
            deleteSlider (id) { // request to the backend to delete specific record
                if(this.$gate.isAdmin()) {
                swal({
                    title: 'Изтриване!',
                    text: `Сигорни ли сте?`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Не, недей!',
                    confirmButtonText: 'Да, изтрий го!'
                    }).then((result) => {
                        if (result.value) {
                            axios.delete('/api/Sliders/' + id)
                            .then(() => {
                                this.getSliders();
                                toast({type: 'success', title: 'Записът е успешно изтрит!'})
                            })
                            .catch(() => {

                            })
                        }
                    })
                }
            },
            changeAdminMode() { //check is the user administrator
                if(this.$gate.isAdmin()) {
                    this.adminMode = true
                } else {
                    this.adminMode = false
                }
            },
            runInterval(){ // change the slider when called
                var currentSlide = $(".active");
                var nextSlide = currentSlide.next();

                currentSlide.fadeOut(0).removeClass("active");
                nextSlide.fadeIn(500).addClass("active");

                if (nextSlide.length == 0) {
                    $(".slider").first().fadeIn(500).addClass("active");
                };
            },
            leftArrow(){ // display previous slider and clear the interval if needed
                if (this.sliderMode) {
                clearInterval(this.sliderInterval);
                }
                var currentSlide = $(".active");
                var prevSlide = currentSlide.prev();

                currentSlide.fadeOut(0).removeClass("active");
                prevSlide.fadeIn(0).addClass("active");

                if (prevSlide.length == 0) {
                    $(".slider").last().fadeIn(0).addClass("active");
                }
                if (this.sliderMode) {
                this.sliderInterval = setInterval(this.runInterval, 6000);
                }
            },
            leftArrowHover() { // change the arrow color
                $('.fa-caret-left').toggleClass('green').toggleClass('orange');
            },
            rightArrowHover() { // change the arrow color
                $('.fa-caret-right').toggleClass('green').toggleClass('orange');
            },
            rightArrow(){ // display next slider and clear the interval if needed
                if (this.sliderMode) {
                    clearInterval(this.sliderInterval);
                }
                var currentSlide = $(".active");
                var nextSlide = currentSlide.next();

                currentSlide.fadeOut(0).removeClass("active");
                nextSlide.fadeIn(0).addClass("active");

                if (nextSlide.length == 0) {
                    $(".slider").first().fadeIn(0).addClass("active");
                }
                if (this.sliderMode) {
                this.sliderInterval = setInterval(this.runInterval, 6000);
                }
            },
        },
        mounted() {
            this.getSliders();
            this.changeAdminMode();
        }
    }
</script>



