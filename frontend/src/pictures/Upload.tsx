import React, {useState} from 'react';
import PropTypes from 'prop-types';
import {createStyles, makeStyles, Theme, withStyles} from '@material-ui/core/styles';
import Button from '@material-ui/core/Button';
import {Mutation} from "react-apollo";
import gql from "graphql-tag";
import CloudUploadIcon from '@material-ui/icons/CloudUpload';
import GridListTile from '@material-ui/core/GridListTile';
import SaveIcon from '@material-ui/icons/Save';
import classNames from "classnames"
import Input from '@material-ui/core/Input';
import GridList from '@material-ui/core/GridList';
import Paper from '@material-ui/core/Paper';
import Typography from '@material-ui/core/Typography';


const useStyles = makeStyles((theme: Theme) => createStyles({
    root: {
        display: 'flex',
        flexWrap: 'wrap',
        justifyContent: 'space-around',
        overflow: 'hidden',
        backgroundColor: theme.palette.background.paper,
        "margin-top": 20
    },
    gridList: {
        flexWrap: 'nowrap',
        // Promote the list into his own layer on Chrome. This cost memory but helps keeping high FPS.
        transform: 'translateZ(0)',
    },
    button: {
        "margin-left": 30
    },
    icon: {
        color: 'rgba(255, 255, 255, 0.54)',
    },
    input: {
        display: 'none',
    },
    leftIcon: {
        marginRight: theme.spacing(1),
    },
    rightIcon: {
        marginLeft: theme.spacing(1),
    },
    iconSmall: {
        fontSize: 20,
    },
    upload: {
        "margin-top": 20,
       // width: 1000,
        "margin-bottom": 20,
    },
    warning: {
        float: 'right',
        padding: 10
    }
}));

const UPLOAD = gql`
    mutation($files: [Upload!]!) {
        uploadPictures(files: $files) {
            id
            contentUrl
        }
    }
`

interface Props {
    refetchQueries: any
}

function UploadForm(props: Props) {
    const classes = useStyles();
    const {refetchQueries} = props;

    const [files, setFiles] = useState();
    const [filesRead, setFilesRead] = useState();

    const handleFileSelect = (e: React.ChangeEvent<HTMLInputElement>) => {
        const filePromises: Array<Promise<URL>> = [];
        if (e.target.files && e.target.files.length) {
            setFilesRead(e.target.files)
            for (let i = 0; i < e.target.files.length; i++) {
                const reader = new FileReader();
                filePromises.push(new Promise((resolve: any) => {
                    reader.onload = () => {
                        resolve(reader.result)
                    };
                    if (e.target.files && e.target.files[i]) {
                        reader.readAsDataURL(e.target.files[i]);
                    }
                    return true;
                }))
            }
        }

        Promise.all(filePromises).then((fileResults) => setFiles(fileResults));
    }

    return (
        <div className={classes.upload}>
            <Mutation<any, any> mutation={UPLOAD} refetchQueries={refetchQueries}>
                {(mutate) => {
                    return (
                        <React.Fragment>
                            <Input
                                type="file"
                                id="raised-button-file"
                                onChange={handleFileSelect}
                                inputProps={{multiple: true, accept: "image/*"}}
                                style={{display: 'none'}}
                            />
                            <Button
                                variant="contained"
                                component="span"
                                color="secondary">
                                <CloudUploadIcon
                                    className={classNames(classes.leftIcon, classes.iconSmall)}
                                />
                                Upload
                            </Button>
                            <Button
                                onClick={() => { mutate({ variables: { files: filesRead } }).then(() => setFiles([])).catch((e) => {setFiles([]); alert("Could not upload files to server, try smaller file size")}) }}
                                variant="contained"
                                color="primary"
                                className={classes.button}>
                                <SaveIcon
                                    className={classNames(classes.leftIcon, classes.iconSmall)}
                                />
                                Save
                            </Button>
                            <Paper className={classes.warning}>
                                <Typography component="p">
                                    You can upload up tp 20 files in a batch, with total size up to 30M.
                                </Typography>
                            </Paper>
                            {files &&
                            <div className={classes.root}>
                              <GridList cols={2.5} className={classes.gridList}>
                                  {files && files.map((file: any) => (
                                      <GridListTile style={{height: '80px'}}>
                                          <img src={file} alt="upload error"/>
                                      </GridListTile>
                                  ))
                                  }
                              </GridList>
                            </div>
                            }
                        </React.Fragment>
                    )
                }
                }
            </Mutation>
        </div>
    );
}

export default UploadForm;

