import React, {Component} from 'react';
import Message from './Message'

class Messages extends Component {
    constructor(props) {
        super(props);
        this.state = {
            messages: props.messages
        };
    }

    componentWillReceiveProps(nextProps) {
        this.setState({messages: nextProps.messages});
    }

    render() {
        return (
            <div className="content">
                {this.state.messages.map(message => {
                    return <Message message={message}/>
                })}
            </div>
        );
    }
}

export default Messages;
