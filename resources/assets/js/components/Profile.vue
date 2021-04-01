<style>
.widget-user-header{
  background-position: center center;
  background-size: cover;
  height: auto;
}

</style>

<template>
  <div class="container">
    <div class="row">
      <div class="col-md-12 mt-3">
            <!-- Widget: user widget style 1 -->
            <div class="card card-widget widget-user">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header text-white" style="background-image:url('./img/user-cover.jpg')">
                <h3 class="widget-user-username text-right">{{this.form.name}}</h3>
                <h5 class="widget-user-desc text-right">{{this.form.type}}</h5>
              </div>
              <div class="widget-user-image">
                <img class="img-circle" :src="getProfilePhoto()" alt="User Avatar">
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">3,200</h5>
                      <span class="description-text">SALES</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">13,000</h5>
                      <span class="description-text">FOLLOWERS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4">
                    <div class="description-block">
                      <h5 class="description-header">35</h5>
                      <span class="description-text">PRODUCTS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
            </div>
            <!-- /.widget-user -->
          </div>
      </div> 
      <div class="col-md-14">
      <div class="card">
      <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
                  <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
      </div>
     <div class="card-body">
      <!-- END timeline item -->
                  <!-- /.tab-pane -->
        <div class="tab-content">        
           <div class="tab-pane" id="activity">
             <h3 class="text-center">Display User Activity</h3>
             </div> 
        <div class="tab-pane active show" id="settings">
                    <form class="form-horizontal">
                      <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                          <input type="email" v-model="form.name" class="form-control" id="inputName" placeholder="Name"
                          :class="{ 'is-invalid': form.errors.has('name')}">
                          <has-error :form="form" field="name"></has-error>
                          </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail" class="col-sm-2 control-label" >Email</label>
                        <div class="col-sm-12">
                          <input type="email" v-model="form.email" class="form-control" id="inputEmail" placeholder="Email"
                           :class="{ 'is-invalid': form.errors.has('email')}">
                           <has-error :form="form" field="email"></has-error>
                      </div>
                      </div>
                      <div class="form-group">
                        <label for="inputExperience" class="col-sm-2 control-label">Experience</label>
                        <div class="col-sm-12">
                          <textarea v-model="form.bio" class="form-control" id="inputExperience" placeholder="Experience" 
                          :class="{ 'is-invalid': form.errors.has('bio')}"></textarea>
                          <has-error :form="form" field="bio"></has-error>
                      </div>
                      </div>
                      <div class="form-group">
                         <label for="Photo" class="col-sm-2 control-label">Profile Photo</label>
                         <div class="col-sm-12">
                       <input type="file" @change="updateProfile" name="Photo" class="form-input">
                       </div>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-12">
                        <label for="inputPassword">Password (leave empty if not changing)</label>
                        </div>
                        <div class="col-sm-12">
                        <input type="password" v-model="form.password" class="form-control" id="password" placeholder="Passport">
                        <has-error :form="form" field="password"></has-error>
                      </div>
                      </div>
                      <div class="form-group row">
                          <div class="col-sm-12">
                          <button @click.prevent="updateInfo" type="submit" class="btn btn-success">update</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                </div>
                </div>
                <!-- /.tab-content -->
              </div>             
    </div>
</template>

<script>
    export default {
      data(){
        //console.log(data);
       return{
        form: new Form({
        id: "",
        name: "",
        email: "",
        password: "",
        type: "",
        bio: "",
        photo: "",
      })}
      },
      
        mounted() {
            console.log('Component mounted.')
        },
         methods:{
           getProfilePhoto(){
             //3ebara 3an if statment ben (condition) ba3ed ? eza tnafd el shar6 e3mal haik ba3ed : m3naha else
             let photo=(this.form.photo.length>200) ? this.form.photo : "img/profile/"+this.form.photo;
             return photo;
             /**  var photo = this.form.photo; //3shan tt3'ayer el swra bdon ma a3mal refresh
                if ( photo.includes('data:image'))
                    return this.form.photo;
                else
                    return ('img/profile/')+this.form.photo;*/
           },
            //this function to send put request to the server, send data
        updateInfo(){
            this.$Progress.start(); //$Progress 7a6et $ la2eno prototyping in java script ro access anywhere in the application
            if (this.form.password==''){
               this.form.password=undefined;
            }
            this.form.put('api/profile/') //send the request
            .then(()=>{
              
              this.$Progress.finish();
              
            })
            .catch(()=>{
              this.$Progress.fail();
            });
        },
        updateProfile(e){
        //console.log('uploading');
        let file = e.target.files[0];
        console.log(file); //hon ra7 ytba3 esem el swra w el size w type w haik sha3'lat
        let reader = new FileReader();
        if(file['size'] < 200000){
        reader.onloadend= (file) =>{
                //console.log('RESULT', reader.result); //hon btba3 zay fa8ara
        this.form.photo=reader.result; //bo5od el swra w b3malelha save fe el server
        }
        reader.readAsDataURL(file);
        }else{
          Swal.fire({
           type: 'error',
           title: 'Oops....',
           text: 'You are Uploading a Large File'
          })
        }
        }
        
        },
         created(){
         axios.get("api/profile")
         .then(({ data }) => (this.form.fill(data)));
            
        }
    }
</script>