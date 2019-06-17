import React from 'react';
import {createStyles, Theme, withStyles} from '@material-ui/core/styles';
import Paper from '@material-ui/core/Paper';
import Typography from '@material-ui/core/Typography';


const styles = (theme: Theme) => createStyles({
    root: {
        ...theme.mixins.gutters(),
        paddingTop: theme.spacing(2),
        paddingBottom: theme.spacing(2),
    },
});


const Home: React.FC = (props: any) => {
    const { classes } = props;

    return (
        <Paper className={classes.root} elevation={1}>
            <Typography variant="h5" component="h3">
                Welcome to TelePoll.
            </Typography>
            <Typography component="p">
                This is an automated system that helps you to create and customize your polls in telegram.
            </Typography>
        </Paper>
    );
}

export default withStyles(styles)(Home);
