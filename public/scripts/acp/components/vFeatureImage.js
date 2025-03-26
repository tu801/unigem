var featureImg = {
    props:['imgDesc', 'imgTitle', 'demo'],
    template: "#vfeature-image",
    data: function () {
        return {
            demofile: '',
        }
    },
    mounted(){
        if ( this.demo !== '' ) {
            this.demofile = this.demo;
        }
    },
    methods: {
        handleFileSelected() {
            this.file = '';
            let uploadedFiles = this.$refs.image.files;
            /*
              Adds the uploaded file to the files array
            */
            for( var i = 0; i < uploadedFiles.length; i++ ){
                this.file = uploadedFiles[i];
            }
            this.demofile = URL.createObjectURL(this.file);
        },
        removeAttach() {
            this.demofile = '';
            this.file = '';
            //remove file input
            const input = this.$refs.image;
            input.type = 'text';
            input.type = 'file';
            // if ( this.avatar.id ) this.avatar_rm = 2;
        },
    }
};