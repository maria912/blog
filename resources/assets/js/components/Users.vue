<template>
  <div class="container">
    <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
      <div class="col-md-12">
        <div class="card" >
          <div class="card-header">
            <h3 class="card-title">User Table</h3>

            <div class="card-tools">
              <button class="btn btn-success" @click="newModal">
                Add New <i class="fa fa-user-plus fa-fw"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Type</th>
                  <th>Registered At</th>
                  <th>Modify</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in users.data" :key="user.id">
                  <td>{{ user.id }}</td>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.type}}</td>
                  <!--<td>{{ user.type | uptext }}</td>-->
                  <td>{{ user.created_at | myDate }}</td>
                  <td>
                    <a href="#" @click="editModal(user)">
                      <i class="fa fa-edit blue"></i>
                    </a>
                    /
                    <a href="#" @click="deleteUser(user.id)">
                      <i class="fa fa-trash red"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body 
          el pagination kam user b2dar ada5el eza 7adadet kam user ma ra7 yzbot ma3i 3shan momken bde yfoot 2000 aw aktar 
          bzbot a7aded kam user-->
          <div class="card-footer">
            <pagination :data="users" 
            @pagination-change-page="getResults"></pagination>
            </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
    <div  v-if="!$gate.isAdminOrAuthor()"> <not-found></not-found> </div> <!-- /if the user not admin -->
    <div
      class="modal fade"
      id="addNew"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" v-show="!editmode" id="addNewLabel">
              Add New
            </h5>
            <h5 class="modal-title" v-show="editmode" id="addNewLabel">
              Update User's Info
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="editmode ? updateUser() : creatUser()">
            <div class="modal-body">
              <div class="form-group">
                <input
                  v-model="form.name"
                  type="text"
                  name="name"
                  placeholder="Name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }"
                />
                <has-error :form="form" field="name"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.email"
                  type="email"
                  name="email"
                  placeholder="Email Address"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('email') }"
                />
                <has-error :form="form" field="email"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.bio"
                  type="bio"
                  name="bio"
                  placeholder="short bio for User (Optional)"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('bio') }"
                />
                <has-error :form="form" field="bio"></has-error>
              </div>
              <div class="form-group">
                <select
                  name="type"
                  v-model="form.type"
                  id="type"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('type') }"
                >
                  <option value="">select User Role</option>
                  <option value="admin">Admin</option>
                  <option value="user">Standard User</option>
                  <option value="author">Author</option>
                </select>
                <has-error :form="form" field="type"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.password"
                  type="password"
                  name="password"
                  id="password"
                  placeholder="Password"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('password') }"
                />
                <has-error :form="form" field="password"></has-error>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">
                Close
              </button>
              <button type="submit" v-show="editmode" class="btn btn-success">
                Update
              </button>
              <button type="submit" v-show="!editmode" class="btn btn-primary">
                Create
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      editmode: false,
      users: {},
      form: new Form({
        id: "",
        name: "",
        email: "",
        password: "",
        type: "",
        bio: "",
        photo: "",
      }),
    };
  },
  methods: {
    getResults(page = 1) {
			axios.get('api/user?page=' + page)
				.then(response => {
					this.users = response.data;
				});
		},
    updateUser() {
      this.$Progress.start();
      // console.log('Editing data');
      this.form.put("api/user/" +this.form.id)
        .then(() => {
          $("#addNew").modal("hide");
          // success
          Swal.fire("Updated!", "information has been updated", "success");
          this.$Progress.finish();
          Fire.$emit("AfterCreate");
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    editModal(user) {
      this.editmode = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(user);
    },
    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },

    deleteUser(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          this.form
            .delete("api/user/" + id)
            .then(() => {
              Swal.fire("Deleted!", "Your file has been deleted.", "success");
              Fire.$emit("AfterCreate");
            })
            .catch(() => {
              Swal.fire("Failed!", "There was something wronge.", "warning");
            });
        }
      });
    },
    loadUsers() {
      // if(this.$gate.isAdmin()) bas admin
     if(this.$gate.isAdminOrAuthor()){
      axios.get("api/user").then(({ data }) => (this.users = data)); 
      }
    },
    creatUser() {
      this.$Progress.start();
      this.form
        .post("api/user")
        .then(() => {
          Fire.$emit("AfterCreate");
          $("#addNew").modal("hide");
          toast.Fire({
            icon: "success",
            title: "Signed in successfully",
          });
          this.$Progress.finish();
        })
        .catch(() => {});
    },
  },
  created() {
    //how to listen the searchit Function
    Fire.$on('searching',() => {
          let query= this.$parent.search; //this.$parent.search take the information from the root app.js the store the information in the query 
              axios.get('api/findUser?q=' + query)
               .then((data) => {
               this.users= data.data //el data ele bteje mn el server
               })
               .catch(() => {

               })
    })
           
        this.loadUsers();
           Fire.$on('AfterCreate',() => {
               this.loadUsers();
           });
  },
  //    setInterval(() => this.loadUsers(), 3000);
};
</script>