import React, {Component} from 'react';
import Linkify from 'react-linkify';

class Message extends Component {
    constructor(props) {
        super(props);
        this.state = {
            message: props.message
        }
    }

    componentWillReceiveProps(nextProps) {
        this.setState({message: nextProps.message});
    }

    render() {
        return (
            <blockquote data-id={this.state.message.id}>
                <Linkify properties={{target: '_blank'}}>
                    {this.state.message.content}
                </Linkify>
            </blockquote>
        );
    }
}

export default Message;
