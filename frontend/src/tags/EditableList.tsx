import React from 'react';
import PropTypes from 'prop-types';
import {createStyles, makeStyles, Theme, withStyles} from '@material-ui/core/styles';
import Chip from '@material-ui/core/Chip';
import Paper from '@material-ui/core/Paper';

const useStyles = makeStyles((theme: Theme) => createStyles({
    root: {
        display: 'flex',
        justifyContent: 'center',
        flexWrap: 'wrap',
        padding: theme.spacing(2),
    },
    chip: {
        margin: theme.spacing(2),
    },
}));

interface Props {
    handleDelete: (tag: Tag) => any;
    tags: Array<Tag>;
}

interface Tag {
    id?: string;
    name: string;
}

function EditableList(props: Props) {
    const classes = useStyles();
    const { tags, handleDelete} = props;

    return (
        <Paper className={classes.root}>
            {tags.map((tag: Tag) =>
                    <Chip
                        key={tag.id}
                        label={tag.name}
                        onDelete={() => handleDelete(tag)}
                        className={classes.chip}
                    />
            )}
        </Paper>
    );
}

export default EditableList;