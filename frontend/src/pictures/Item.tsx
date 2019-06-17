import React, {SyntheticEvent, useState} from 'react';
import {createStyles, makeStyles, Theme} from '@material-ui/core/styles';
import {Query, Mutation} from "react-apollo";
import {gql} from "apollo-boost";
import EditableList from "../tags/EditableList";
import TextField from '@material-ui/core/TextField';
import Button from '@material-ui/core/Button';
import DeleteIcon from '@material-ui/icons/Delete';

function getModalStyle() {
    return {
        top: `50%`,
        left: `50%`,
        transform: `translate(-50%, -50%)`,
    };
}

const useStyles = makeStyles((theme: Theme) => createStyles({
    paper: {
        position: 'absolute',
        backgroundColor: theme.palette.background.paper,
        boxShadow: theme.shadows[5],
        padding: theme.spacing(4),
        outline: 'none',
    },
}));

interface Props {
    id: string
    closeModal: any
    refetchQueries: any
}

interface Picture {
    id?: string;
    contentUrl?: string;
    tags: Array<Tag>
}

interface PictureInput {
    pic: Picture
}

const PICTURE_QUERY = gql`
    query Picture($id: ID!) {
        picture(id: $id) {
            contentUrl
            id
            tags {
                id
                name
            }
        }
    }
`;

const UPDATE_PICTURE = gql`
    mutation UpdatePicture($pic: PictureInput!) {
        updatePicture(input: $pic) {
            id
            contentUrl
            tags {
                id
                name
            }
        }
    }
`;

const DELETE_PICTURE = gql`
    mutation DeletePicture($pic: PictureInput!) {
        deletePicture(input: $pic)
    }
`

interface Tag {
    id?: string;
    name: string;
}

interface Data {
    picture: Picture
}

interface Variables {
    id: string;
}

function Item(props: Props) {

    const classes = useStyles();
    const { id, closeModal, refetchQueries} = props;

    const [newTag, setNewTag] = useState('');

    const handleDelete = (updateMutation: any, tag: Tag, tags: Array<Tag>) => {
        const tagToDelete = tags.indexOf(tag);
        tags.splice(tagToDelete, 1);
        updateMutation(
            {
                variables: {
                    "pic": {
                        "id": id,
                        "tags": tags
                    }
                }
            });
    };

    const handleChange = (event: any) => {
        setNewTag(event.target.value);
    };

    const handleSubmit = (formData: SyntheticEvent, update: any, tags: Array<Tag>) => {
        formData.preventDefault();
        //const tagsArray = tags.map((tag: Tag) => {return {name: tag.name}});
        //tagsArray.push({name:newTag})
        tags.push({name: newTag});
        update({
            variables: {
                "pic": {
                    "id": id,
                    "tags": tags
                }
            }
        })
        setNewTag('');
    };

    return (
        <Query<Data, Variables> query={PICTURE_QUERY} variables={{id}}>
            {({loading, error, data}) => {
                if (loading) return <p>Loading...</p>;
                if (error) return <p>Error :(</p>;
                if (!data || !data.picture) {
                    alert("Not found")
                }
                return (

                    <div style={getModalStyle()} className={classes.paper}>
                        <Mutation<any, Picture> mutation={UPDATE_PICTURE}>
                            {(updatePicture) => {
                                let tags: Array<Tag>;
                                if (data) {
                                    tags = data.picture.tags
                                } else {
                                    tags = [];
                                }
                                return <React.Fragment>
                                    <Mutation<any, PictureInput>
                                        mutation={DELETE_PICTURE}
                                        refetchQueries={refetchQueries}
                                    >
                                        {(deletePicture) => {
                                            const pic = data && data.picture;
                                            if (!pic) {
                                                return
                                            }
                                            return (
                                                <Button
                                                    onClick={() => {
                                                        deletePicture({variables: {pic: pic}});
                                                        closeModal();
                                                    }}
                                                    variant="text"
                                                    component="span"
                                                    color="secondary">
                                                    <DeleteIcon

                                                    />
                                                    Delete
                                                </Button>
                                            )
                                        }
                                        }
                                    </Mutation>
                                    <form noValidate autoComplete="off"
                                          onSubmit={(e: SyntheticEvent) => handleSubmit(e, updatePicture, tags)}>
                                        <TextField
                                            id="standard-name"
                                            label="Enter new tag"
                                            value={newTag}
                                            onChange={handleChange}
                                            margin="normal"
                                        />
                                    </form>
                                    {
                                        data && data.picture &&
                                        <React.Fragment>
                                          <EditableList tags={data.picture.tags}
                                                        handleDelete={(tag: Tag) => handleDelete(updatePicture, tag, tags)}/>
                                          <img height="400" src={data.picture.contentUrl} alt="img"/>
                                        </React.Fragment>
                                    }
                                </React.Fragment>
                            }}
                        </Mutation>
                    </div>);
            }}
        </Query>
    );
}

export default Item;