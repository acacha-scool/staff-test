<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Choose an user:</div>

                    <div class="panel-body">
                        <multiselect v-model="selectedUser"
                                     :options="users"
                                     :custom-label="nameWithLang"
                                     placeholder="Select one" label="name"
                                     track-by="name"></multiselect>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>


<script>

  import Multiselect from 'vue-multiselect'

  export default {
    components: {
      Multiselect
    },
    data () {
      return {
        selectedUser: {},
        users: []
      }
    },
    watch: {
      selectedUser: function (val) {
        this.$emit('userHasBeenSelected', this.selectedUserId)
      }
    },
    computed: {
      selectedUserId: function () {
        if (this.selectedUser) return this.selectedUser.id
        else return null
      }
    },
    methods: {
      nameWithLang ({ name, personalId, email, username }) {
        return `${name} — [${personalId} - ${email} - ${username}  ]`
      },
      fetchUsers () {
        this.users = [
          { id: 1, name: 'Sergi Tur Badenas', personalId: '14232003K', email: 'sergiturbadenas@gmail.com', username: 'sergitur'  },
          { id: 2, name: 'Pepe Pardo Jeans', personalId: '14782003K', email: 'pepe@gmail.com', username: 'pepepardo' },
          { id: 3, name: 'Marieta deLaHoz Estel', personalId: '14743003K', email: 'sda@gmail.com', username: 'marietahoz' },
          { id: 4, name: 'Bego Solé Aragonés', personalId: '14232004K', email: 'fdwefds@gmail.com', username: 'begosole' },
          { id: 5, name: 'Xavier Tur Solé', personalId: '14233003K', email: 'das@gmail.com', username: 'xavitur' },
          { id: 6, name: 'Romeu Tur Solé', personalId: '14234863K' , email: 'saddas@gmail.com', username: 'romeutur' }
        ]
      },
      selectDefaultUser() {
//        this.selectedUser = { id: 1, name: 'Sergi Tur Badenas', personalId: '14232003K', email: 'sergiturbadenas@gmail.com', username: 'sergitur' }
        this.selectedUser = null
      },
      choose() {
        if (this.selectedUser) this.submit()
        else this.showUserNotSelectedError()
      },
      showUserNotSelectedError() {
        console.log('No user has been selected!')
      },
      submit() {
        console.log('Submitting!');
        //send event to father
      }
    },
    mounted() {
        this.fetchUsers()
        this.selectDefaultUser()
    }
  }

</script>
