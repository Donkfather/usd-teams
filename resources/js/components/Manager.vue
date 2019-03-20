<template>
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-md-8mb-2">
                <form class="form-inline" @submit.prevent="createTeam">
                    <div class="form-group">
                        <div class="d-flex flex-column">
                            <input type="text"
                                   v-model="newTeamForm.name"
                                   class="form-control flex" name="team_name" id="team_name"
                                   placeholder="Team name">
                            <small class="form-text text-danger" v-if="newTeamForm.error"
                                   v-text="newTeamForm.error"></small>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-link" @click="">Create team</button>
                </form>
            </div>
        </div>
        <div class="row justify-content-center mb-4" v-for="team in teams">

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div v-text="team.name"></div>
                        <button class="btn btn-link" @click="showAddPlayerDialog(team)">Add player</button>
                    </div>

                    <div class="card-body">
                        <ul>
                            <li v-for="player in team.players" :key="player.id" v-text="player.name"></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <b-modal ref="playerForm" hide-footer :title="playerModal.title">
            <form @submit.prevent="handlePlayerSubmit">
                <div class="form-group">
                    <input type="text" class="form-control"
                           name="first_name"
                           id="first_name"
                           placeholder="First name">
                </div>
                <div class="form-group">
                    <input type="text"
                           class="form-control"
                           name="last_name"
                           id="last_name"
                           placeholder="Last name">
                </div>
                <button type="submit" class="btn btn-primary">Add</button>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import BModal from 'bootstrap-vue/es/components/modal/modal'

    export default {
        name: "Manager",
        components: {
            'b-modal': BModal
        },
        data() {
            return {
                newTeamForm: {
                    name: "",
                    error: null
                },
                playerModal: {
                    show: false,
                    edit: false,
                    title: "",
                    form: {
                        first_name: "",
                        last_name: "",
                        team: null,
                    },
                },
                teams: []
            };
        },
        created() {
            axios.get('/api/teams').then(response => {
                this.teams.push(...response.data)
            })
        },
        methods: {
            showAddPlayerDialog(team) {
                this.playerModal.edit = false;
                this.playerModal.form.team = team;
                this.playerModal.show = true;
                this.playerModal.title = `Add new player to team: <strong>${team.name}</strong>`;

                this.$refs.playerForm.show();
            },
            createTeam() {
                if (this.newTeamForm.name) {
                    this.newTeamForm.error = null;
                    axios.post('/api/teams', {
                        name: this.newTeamForm.name
                    })
                        .then(response => {
                            this.teams.unshift(response.data)
                        })
                        .catch(err => {
                            if (err.response && err.response.status === 422) {
                                this.newTeamForm.error = err.response.data.errors.name[0]
                            }
                        })

                }
            },
            addPlayer(team) {
                axios.post(`/api/teams/${team.id}/players`, {})
            },
            handlePlayerSubmit(){

            }
        }
    }
</script>
