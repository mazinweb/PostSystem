<template>
    <div>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-success btn-sm float-right" data-toggle="modal" data-target="#exampleModal">
            New Post
        </button>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-if="edit" class="modal-title" id="exampleModalLabel">Edit Post</h5>
                        <h5 v-else class="modal-title" id="exampleModalLabel">Create Post</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" :class="['form-control my-2',arrayerrors.title?'is-invalid':'']" placeholder="title" v-model="objpost.title"/>
                        <span v-if="arrayerrors.title" class="bg-danger text-white p-1 rounded">{{arrayerrors.title[0]}}</span>
                        <textarea :class="['form-control my-2',arrayerrors.title?'is-invalid':'']" placeholder="body" v-model="objpost.body"></textarea>
                        <span v-if="arrayerrors.body" class="bg-danger text-white p-1 rounded">{{arrayerrors.body[0]}}</span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button v-if="edit" type="button" class="btn btn-success" @click="updatePost">Update</button>
                        <button v-else type="button" class="btn btn-success" @click="createPost">Create</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- show post from database -->
        <div v-for="post in allposts" :key="post.id" class="my-3">
            <h4>{{post.title}}</h4>
            <p>{{post.body}}</p>
            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#exampleModal" @click="editPost(post)">
                Edit Post
            </button>
            <button type="button" class="btn btn-danger btn-sm" @click="deletePost(post)">
                Delete Post
            </button>
        </div>
        <!-- end show post from database -->
    </div>
</template>
<script>
export default {
    data(){
        return{
            objpost:{
                id:'',
                title:'',
                body:'',
            },
            edit:false,
            allposts:{},
            arrayerrors:[]
        }
    },
    methods:{
        createPost(){
            axios.post('api/createnewpost',this.objpost).then(Response=>{
                if(Response.data.status == 'error'){
                    this.arrayerrors = Response.data.errors
                }
                else if (Response.data.status == 'success') {
                    this.allposts.unshift(Response.data.data)
                    Toast.fire({
                        icon: 'success',
                        title: 'Created successfully'
                        })
                    this.arrayerrors = []
                    this.objpost = {
                        id:'',
                        title:'',
                        body:''
                    }
                }
            })
        },
        updatePost(){
            axios.put('api/updateoldpost/'+this.objpost.id,this.objpost).then(Response=>{
                if(Response.data.status == 'error'){
                    this.arrayerrors = Response.data.errors
                }
                else if (Response.data.status == 'success') {
                    Toast.fire({
                        icon: 'success',
                        title: 'Updated successfully'
                        })
                    this.arrayerrors = []
                    this.objpost = {
                        id:'',
                        title:'',
                        body:''
                    }
                }
            })
        },
        deletePost(delpostid){
            //alert(delpostid.id)
            axios.delete('api/deleteoldpost/'+delpostid.id,this.objpost).then(Response=>{
                if(Response.data.status == 'error'){
                    this.arrayerrors = Response.data.errors
                }
                else if (Response.data.status == 'success') {
                    this.allposts = Response.data.data;
                    Toast.fire({
                        icon: 'success',
                        title: 'Deleted successfully'
                        })
                }
            })
        },
        getPost(){
            axios.get('api/getPostinfo').then(Response =>{
                this.allposts = Response.data.data;
            })
        },
        editPost(post){
            this.objpost = post;
            this.edit = true;
        }
    },
    created(){
        this.getPost();
    }
}
</script>