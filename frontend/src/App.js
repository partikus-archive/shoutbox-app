import React from 'react';
import '../node_modules/bulma/css/bulma.min.css';


import Messages from './Shoutbox/Messages';
import MessageBox from "./Shoutbox/MessageBox";

class App extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            messages: []
        };
        this._connectWs();
    }

    _connectWs() {
        this.connection = new WebSocket('ws://localhost:8080/messages');
        this.connection.onclose = (e) => {
            this._connectWs();
        }
    }

    componentDidMount() {
        this.onMessageSend = this.onMessageSend.bind(this);
        this.connection.onmessage = (e) => {
            const decodeData = JSON.parse(e.data);
            if (Array.isArray(decodeData)) {
                this.setState({messages: decodeData});
            } else {
                this.setState({messages: [decodeData, ...this.state.messages].slice(0, 5)});
            }
        };
    }

    componentWillUnmount() {
        this.connection.close();
    }

    onMessageSend() {
        return (message) => {
            this.connection.send(message);
        }
    }

    render() {
        return (
            <div className="App">
                <div className="container is-fluid">
                    <div className="row">
                        <MessageBox onMessageSend={this.onMessageSend()}/>
                    </div>
                    <div className="row">
                        <Messages messages={this.state.messages}/>
                    </div>
                </div>
            </div>
        );
    }
}

export default App;
