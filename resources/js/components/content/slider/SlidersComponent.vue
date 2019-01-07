<template>
<div class="row flex-xl-nowrap">
    <a href="#" class="float-right" v-if="sliderAdminMode" @click.prevent="showModal()"><i class="fas fa-file-import fa-3x green"></i></a>
    <div class="col-12 col-md-9">
        <div>
            <div class="sliderNav d-flex justify-content-between" v-once>
                <i @mouseout="leftArrowHover()" @mouseover="leftArrowHover()" class="fas fa-caret-left fa-4x green" @click="leftArrow()"></i>
                <i @mouseout="rightArrowHover()" @mouseover="rightArrowHover()" class="fas fa-caret-right fa-4x green" @click="rightArrow()"></i>
            </div>
            <div class="sliderContiner">
                <div  v-for="(slider, index) in sliders" :key="slider.id" v-bind:class="{ active: index==0, slider}">
                    <div v-if="sliderMode">
                        <h3 class="text-center dwhite">{{ slider.title }}</h3>
                        <a href="#" @click.prevent="choiseSlider()">
                        <img :src="'/storage/sliders/' + slider.photos" :alt="slider.title">
                        </a>
                    </div>
                    <div v-if="!sliderMode">
                        <h3 class="text-center dwhite">{{ slider.title }}aaaaaaaaaaaaaaaaaa</h3>
                        <p class="dwhite pr-2">
                            <a href="#" @click.prevent="choiseSlider()">
                                <img class="w-50 d-inline float-left" :src="'/storage/sliders/' + slider.photos" :alt="slider.title">
                            </a>{{ slider.description }}
                        </p>
                        <div class="p-1 d-inline-block w-100">
                            <hr v-if="!SliderAdminMode" class="mb-0 mt-0">
                            <a href="#" v-if="sliderAdminMode" @click.prevent="editSlider(slider)"><i class="fas fa-edit yellow"></i>&#160;</a>
                            <a href="" v-if="sliderAdminMode" @click.prevent="deleteSlider(slider.id)"><i class="fas fa-trash red"></i>&#160;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="sliderModal" tabindex="-1" role="dialog" aria-labelledby="sliderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sliderModalLabel">Добавяне на слайд</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateSlider() : createSlider()" id="slidersForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.title" type="text" name="title"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('title') }" placeholder="Заглавие" required>
                                <has-error :form="form" field="title"></has-error>
                            </div>
                            <div class="form-group">
                             <input type="file" @change="onFileSelect" name="photos" class="form-control">
                            </div>
                            <div class="form-group">
                                <textarea v-model="form.description" type="text" name="description"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('description') }" placeholder="Описание" required></textarea>
                                <has-error :form="form" field="description"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" v-show="editMode" class="btn btn-primary">Промени</button>
                            <button type="submit" v-show="!editMode" class="btn btn-success">Добави</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Затвори</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <announcement-component></announcement-component>
</div>
</template>

<script>
    export default {
        data() {
            return {
                sliderAdminMode: false,
                sliders: null,
                slider: null,
                sliderInterval : setInterval(this.runInterval, 6000),
                sliderMode : true,
                editMode : false,
                form: new Form({
                    id: null,
                    title : null,
                    photos : null,
                    description : null,
                }),
            }
        },
        methods :{
            getSliders() {
                axios.get("/api/Sliders")
                .then(({ data }) => {this.sliders = data.data});
            },
            choiseSlider() {
                if (this.sliderMode) {
                this.sliderMode = false;
                clearInterval(this.sliderInterval);
                } else {
                    this.sliderMode = true;
                    this.sliderInterval = setInterval(this.runInterval, 6000);
                }
            },
            showModal(){
                this.editMode = false
                this.form.reset();
                $("#sliderModal").modal("show");
            },
            onFileSelect(event) {
                let file = event.target.files[0];
                this.form.photos = event.target.files[0];
                let reader = new FileReader();
                reader.onloadend = () => {
                   this.form.photos = reader.result
                }
                reader.readAsDataURL(file)
            },
            createSlider(){
                if(this.$gate.isAdmin()) {
                    this.form.post('/api/Sliders')
                    .then(() => {
                        toast({type: 'success', title: 'Успешно добавихте нов слайд!'})
                        $('#sliderModal').modal('hide');
                        this.getSliders()
                    })
                    .catch(() => {

                    })
                }
            },
            editSlider(slider){
                this.editMode = true;
                $("#sliderModal").modal("show");
                this.form.fill( slider );
            },
            updateSlider() {
                this.form.patch('/api/Sliders/' + this.form.id)
                .then(() => {
                    $('#sliderModal').modal('hide')
                    this.getSlider();
                })
                .catch(() => {
                })
            },
            deleteSlider (id) {
                axios.delete('/api/Sliders/' + id)
                .then(() => {
                    this.getSlider();
                })
                .catch(() => {

                })
            },
            changeMode() {
                if(this.$gate.isAdmin()) {
                    this.sliderAdminMode = true
                } else {
                    this.sliderAdminMode = false
                }
            },
            runInterval(){
                var currentSlide = $(".active");
                var nextSlide = currentSlide.next();

                currentSlide.fadeOut(0).removeClass("active");
                nextSlide.fadeIn(500).addClass("active");

                if (nextSlide.length == 0) {
                    $(".slider").first().fadeIn(500).addClass("active");
                };
            },
            leftArrow(){
                if (sliderMode) {
                clearInterval(this.sliderInterval);
                }
                var currentSlide = $(".active");
                var prevSlide = currentSlide.prev();

                currentSlide.fadeOut(0).removeClass("active");
                prevSlide.fadeIn(500).addClass("active");

                if (prevSlide.length == 0) {
                    $(".slider").last().fadeIn(500).addClass("active");
                }
                if (sliderMode) {
                this.sliderInterval = setInterval(this.runInterval, 6000);
                }
            },
            leftArrowHover() {
                $('.fa-caret-left').toggleClass('green').toggleClass('orange');
            },
            rightArrowHover() {
                $('.fa-caret-right').toggleClass('green').toggleClass('orange');
            },
            rightArrow(){
                if (this.sliderMode) {
                    clearInterval(this.sliderInterval);
                }
                var currentSlide = $(".active");
                var nextSlide = currentSlide.next();

                currentSlide.fadeOut(0).removeClass("active");
                nextSlide.fadeIn(500).addClass("active");

                if (nextSlide.length == 0) {
                    $(".slider").first().fadeIn(500).addClass("active");
                }
                if (this.sliderMode) {
                this.sliderInterval = setInterval(this.runInterval, 6000);
                }
            },
        },
        mounted() {
            this.getSliders();
            this.changeMode();
        }
    }
</script>



