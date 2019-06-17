import React, {useState} from 'react';
import {createStyles, makeStyles, Theme, withStyles} from '@material-ui/core/styles';
import GridList from '@material-ui/core/GridList';
import GridListTile from '@material-ui/core/GridListTile';
import GridListTileBar from '@material-ui/core/GridListTileBar';
import IconButton from '@material-ui/core/IconButton';
import Button from '@material-ui/core/Button';
import Box from '@material-ui/core/Box';
import InfoIcon from '@material-ui/icons/Info';
import {Query} from "react-apollo";
import {gql} from "apollo-boost";
import UploadForm from "./Upload";
import Item from "./Item";
import Modal from '@material-ui/core/Modal';
import Container from '@material-ui/core/Container';


const useStyles = makeStyles((theme: Theme) => createStyles({
    root: {
        display: 'flex',
        flexWrap: 'wrap',
        justifyContent: 'space-around',
        overflow: 'hidden',
        backgroundColor: theme.palette.background.paper,
    },
    button: {
        marginLeft: theme.spacing(2),
    },
    icon: {
        color: 'rgba(255, 255, 255, 0.54)',
    },
    fab: {
        margin: theme.spacing(1),
    },
}));

interface Picture {
    id?: string;
    contentUrl?: string;
}

export const ALL_PICTURES_QUERY = gql`
    query pictures($after: String, $first: Int) {
        pictures(first: $first, after: $after) {
            edges {
                node {
                    id
                    contentUrl
                }
            }
            pageInfo {
                startCursor
                endCursor
                hasNextPage
            }
            totalCount
        }
    }
`;


interface Data {
    pictures: {
        edges: Array<{
            node: Picture
            __typename: string
        }>
        pageInfo: {
            startCursor: string,
            endCursor: string,
            hasNextPage: boolean
        },
        totalCount: number
        __typename: string
    }
}

interface Variables {
    before?: string;
    after?: string;
    first: number;
}

const PAGE_SIZE = 24;

function List() {
    const classes = useStyles();

    const [modalState, setModalState] = useState('');
    const [pagination, setPagination] = useState({first: PAGE_SIZE, after: ""});

    const closeModal = () => setModalState('');
    const openModal = (id: string) => setModalState(id)

    const updateQuery = (previousResult: Data, {fetchMoreResult}: any) => {
        const newEdges = fetchMoreResult ? fetchMoreResult.pictures.edges : [];
        const pageInfo = fetchMoreResult ? fetchMoreResult.pictures.pageInfo : {
            startCursor: "",
            endCursor: "",
            hasNextPage: false
        };
        const totalCount = fetchMoreResult ? fetchMoreResult.pictures.totalCount : 0;

        return newEdges.length
            ? {
                // Put the new comments at the end of the list and update `pageInfo`
                // so we have the new `endCursor` and `hasNextPage` values

                pictures: {
                    __typename: previousResult.pictures.__typename,
                    edges: newEdges,
                    pageInfo,
                    totalCount
                }
            }
            : previousResult;
    }

    return (
        <React.Fragment>
            <Query<Data, Variables> query={ALL_PICTURES_QUERY} variables={pagination}>
                {({loading, error, data, fetchMore}) => {
                    if (loading) return <p>Loading...</p>;
                    if (error) return <p>Error :(</p>;
                    return (
                        <Container maxWidth="lg">
                            <UploadForm refetchQueries={[{
                                query: ALL_PICTURES_QUERY,
                                variables: {first: PAGE_SIZE, after: ""}
                            }]}/>
                            <GridList cellHeight={180} cols={6} >
                                {data && data.pictures.edges.map(({node}) => (
                                    <GridListTile key={node.contentUrl}>
                                        <img src={node.contentUrl} alt={node.id}
                                             onClick={() => openModal(node.id || '')}/>
                                        <GridListTileBar
                                            title={node.id}
                                            actionIcon={
                                                <IconButton className={classes.icon}>
                                                    <InfoIcon/>
                                                </IconButton>
                                            }
                                        />
                                    </GridListTile>
                                ))}
                            </GridList>
                            <Box pt={3} pb={4}>
                            <Button
                                    color="primary"
                                    variant="contained"
                                    component="span"
                                    className={classes.button}
                                    onClick={() => {
                                        return fetchMore({
                                            variables: {
                                                before: data ? data.pictures.pageInfo.startCursor : "",
                                                first: PAGE_SIZE
                                            },
                                            updateQuery
                                        })
                                    }}>
                                Prev page
                            </Button>
                            <Button color="primary"
                                    variant="contained"
                                    component="span"
                                    onClick={() => {
                                        return fetchMore({
                                            variables: {
                                                after: data ? data.pictures.pageInfo.endCursor : "",
                                                first: PAGE_SIZE
                                            },
                                            updateQuery
                                        })
                                    }}>
                                Next page
                            </Button>
                            </Box>
                            <Modal
                                aria-labelledby="simple-modal-title"
                                aria-describedby="simple-modal-description"
                                open={!!modalState}
                                onClose={closeModal}
                            >
                                <Item id={modalState} closeModal={closeModal}
                                      refetchQueries={[{
                                          query: ALL_PICTURES_QUERY, variables: {
                                              after: "",
                                              first: data ? data.pictures.edges.length : PAGE_SIZE
                                          }
                                      }]}/>
                            </Modal>
                        </Container>);
                }}
            </Query>
        </React.Fragment>
    );
}

export const ListOfPictures = List;