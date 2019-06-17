import React from 'react';
import Home from './Home';
import { ListOfPictures as List } from './pictures/List'
import Upload from './pictures/Upload'
import {BrowserRouter as Router, Link, Route} from 'react-router-dom';
import {createStyles, Theme, AppBar, CssBaseline, Typography, Toolbar, withStyles} from "@material-ui/core";
import HomeIcon from '@material-ui/icons/Home';
import { ApolloProvider } from "react-apollo";
import {client} from './api/apiClient'

const styles = (theme :Theme) => createStyles({
    appBar: {
        position: 'relative',
        color: 'white',
    },
    icon: {
        marginRight: theme.spacing(2),
        color: 'white'
    },
    footer: {
        backgroundColor: theme.palette.background.paper,
        padding: theme.spacing(6),
    },
    link: {
        color: 'white',
        "text-decoration": 'none'
    },
    menuLink: {
        margin: "10px"
    }
});

const App: React.FC = (props: any) => {
    const { classes } = props;

  return (
      <ApolloProvider client={client}>
        <Router>
            <CssBaseline />
            <AppBar position="static" className={classes.appBar}>
                <Toolbar>
                    <Link to={"/"}>
                        <HomeIcon className={classes.icon} />
                    </Link>
                    <Typography variant="h6" color="inherit" className={classes.menuLink}>
                        <Link to={"/pictures"} className={classes.link}>Pictures</Link>
                    </Typography>
                    <Typography variant="h6" color="inherit" className={classes.menuLink}>
                        <Link to={"/bots"} className={classes.link}>Bots</Link>
                    </Typography>
                </Toolbar>
            </AppBar>
            <main>
            <div>
                <Route exact path="/" component={Home} />
                <Route exact path="/pictures" component={List} />
                <Route exact path="/pictures/upload" component={Upload} />
                <Route path="/bots" component={Home} />
            </div>
            </main>
        </Router>
      </ApolloProvider>
  );
}

export default withStyles(styles)(App);
